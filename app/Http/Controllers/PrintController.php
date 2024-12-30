<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;

class PrintController extends Controller
{
    public function printQueue(Request $request)
    {
        // Ambil data dari request
        $category = $request->input('category', 'Tidak Diketahui');
        $number = $request->input('number', '000');
        $remainingQueues = $request->input('remaining_queues', 0);
        $timestamp = now()->format('d-m-Y H:i:s');

        try {
            // Log data permintaan untuk debugging
            Log::info('Permintaan yang diterima:', [
                'category' => $category,
                'number' => $number,
                'remaining_queues' => $remainingQueues,
            ]);

            // Sambungkan ke printer thermal
            $connector = new WindowsPrintConnector("POS-58");
            $printer = new Printer($connector);

            // Path gambar BMP atau PNG
            $logoPath = public_path('assets/img/logo.jpg'); // Pastikan BMP atau PNG
            if (file_exists($logoPath)) {
                try {
                    $logo = EscposImage::load($logoPath, false);
                    $printer->setJustification(Printer::JUSTIFY_CENTER); // Atur gambar ke tengah
                    $printer->bitImage($logo);
                } catch (\Exception $e) {
                    Log::error('Gagal memuat gambar:', ['error' => $e->getMessage()]);
                    throw new \Exception("Gagal mencetak gambar. Format tidak didukung.");
                }
            } else {
                Log::warning('Logo tidak ditemukan:', ['path' => $logoPath]);
            }

            // Cetak Header
            $printer->setJustification(Printer::JUSTIFY_CENTER); // Pastikan header juga di tengah
            $printer->text("\n");
            $printer->text("Nomor Antrian Anda\n");
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH | Printer::MODE_DOUBLE_HEIGHT);
            $printer->text("\n");
            $printer->text("\n");
            $printer->text("$number\n");
            $printer->text("\n");
            $printer->text("\n");
            $printer->selectPrintMode(); // Reset ke mode normal
            $printer->text("Kategori: $category\n");
            $printer->text("\n");
            $printer->text("Waktu: $timestamp\n");
            $printer->text("Sisa Antrian: $remainingQueues\n");
            $printer->text("--------------------------------\n");
            $printer->feed(2);

            // Potong kertas
            $printer->cut();

            // Tutup koneksi ke printer
            $printer->close();

            Log::info('Proses pencetakan selesai tanpa kesalahan.');

            return response()->json([
                'success' => true,
                'message' => 'Nomor antrian berhasil dicetak',
            ]);
        } catch (\Exception $e) {
            // Tangkap dan log kesalahan
            Log::error('Kesalahan Pencetakan:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Kesalahan saat mencetak: ' . $e->getMessage(),
            ], 500);
        }
    }
}
