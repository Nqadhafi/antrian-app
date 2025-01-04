<?php

namespace App\Filament\Pages;

use App\Models\Queue;
use App\Models\Category;
use Filament\Pages\Page;
use App\Events\QueueUpdated;

class QueueManagement extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationLabel = 'Antrian Kedatangan';
    protected static string $view = 'filament.pages.queue-management';

    /**
     * Panggil antrian berikutnya yang pertama kali dibuat.
     */
    public function callNext()
    {
        $queue = Queue::where('is_called', false)
            ->orderBy('created_at') // Urutkan berdasarkan waktu pembuatan
            ->first();

        if ($queue) {
            $queue->update(['is_called' => true]);

            // Mengurangi sisa antrian berdasarkan kategori
            $remainingQueues = Queue::where('category_id', $queue->category_id)
                ->where('is_called', false)
                ->count();

            // Broadcast event untuk memberi tahu perubahan
            event(new QueueUpdated(
                $queue->category_id,
                $remainingQueues,
                $queue->category->name,
                $queue->number,
                true // Event berasal dari admin panel
            ));

            $this->notify('success', "Antrian {$queue->number} telah dipanggil!");
        } else {
            $this->notify('warning', 'Tidak ada antrian yang tersedia.');
        }
    }

    /**
     * Panggil ulang antrian terakhir yang telah dipanggil.
     */
    public function recallLast()
    {
        $queue = Queue::where('is_called', true)
            ->latest('updated_at') // Ambil yang terakhir dipanggil
            ->first();

        if ($queue) {
            $remainingQueues = Queue::where('category_id', $queue->category_id)
                ->where('is_called', false)
                ->count();

            // Broadcast ulang event untuk memberi tahu perubahan
            event(new QueueUpdated(
                $queue->category_id,
                $remainingQueues,
                $queue->category->name,
                $queue->number,
                true
            ));

            $this->notify('success', "Antrian {$queue->number} dipanggil ulang!");
        } else {
            $this->notify('warning', 'Tidak ada antrian yang dapat dipanggil ulang.');
        }
    }

    /**
     * Reset semua nomor antrian.
     */
    public function resetQueue()
{
    // Ambil semua kategori yang memiliki antrian
    $categories = Category::all();

    // Hapus semua antrian
    Queue::query()->delete();

    // Kirim event untuk setiap kategori
    foreach ($categories as $category) {
        event(new QueueUpdated(
            $category->id,
            0, // Semua antrian dalam kategori ini direset
            $category->name,
            null // Tidak ada nomor antrian
        ));
    }

    // Kirim satu event umum untuk semua kategori
    event(new QueueUpdated(
        null,
        0, // Semua antrian direset
        'Semua Kategori',
        null,
        true // Event berasal dari admin panel
    ));

    $this->notify('success', 'Semua nomor antrian telah direset.');
}


    /**
     * Ambil data antrian yang telah dibuat dan statusnya.
     */
    public function getQueuesProperty()
    {
        return Queue::with('category')->get(); // Ambil semua antrian
    }
}
