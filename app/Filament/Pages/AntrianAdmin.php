<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\Queue;
use Filament\Pages\Page;
use App\Events\QueueUpdated;

class AntrianAdmin extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationLabel = 'Antrian Kategori';
    protected static string $view = 'filament.pages.antrian-admin';

    /**
     * Panggil antrian berikutnya berdasarkan kategori.
     */
    public function callNext($categoryId)
    {
        $queue = Queue::where('category_id', $categoryId)
            ->where('is_called', false)
            ->orderBy('id')
            ->first();

        if ($queue) {
            $queue->update(['is_called' => true]);

            $remainingQueues = Queue::where('category_id', $categoryId)
                ->where('is_called', false)
                ->count();

            // Broadcast event dengan nomor antrian
            event(new QueueUpdated(
                $categoryId,
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
    public function recallLast($categoryId)
    {
        $queue = Queue::where('category_id', $categoryId)
            ->where('is_called', true)
            ->latest('updated_at')
            ->first();

        if ($queue) {
            $remainingQueues = Queue::where('category_id', $categoryId)
                ->where('is_called', false)
                ->count();

            // Broadcast ulang event dengan nomor antrian
            event(new QueueUpdated(
                $categoryId,
                $remainingQueues,
                $queue->category->name,
                $queue->number,
                true // Event berasal dari admin panel
            ));
            

            $this->notify('success', "Antrian {$queue->number} dipanggil ulang!");
        } else {
            $this->notify('warning', 'Tidak ada antrian yang dapat dipanggil ulang.');
        }
    }

    /**
     * Reset semua nomor antrian dalam kategori tertentu.
     */
    public function resetQueue($categoryId)
    {
        Queue::where('category_id', $categoryId)->delete();

        // Broadcast event untuk memperbarui jumlah sisa antrian
        event(new QueueUpdated(
            $categoryId,
            0, // Semua antrian direset
            Category::find($categoryId)->name,
            null // Tidak ada nomor antrian
        ));

        $this->notify('success', 'Nomor antrian telah direset.');
    }

    /**
     * Tangani event saat antrian baru ditambahkan.
     */
    public function handleQueueUpdated($categoryId, $remainingQueues)
    {
        $category = Category::find($categoryId);

        if ($category) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => "Antrian baru ditambahkan untuk kategori {$category->name}. Sisa antrian: {$remainingQueues}."
            ]);
        } else {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'warning',
                'message' => "Kategori dengan ID {$categoryId} tidak ditemukan."
            ]);
        }
    }

    /**
     * Ambil data kategori beserta jumlah antrian yang belum dipanggil.
     */
    public function getCategoriesProperty()
    {
        return Category::withCount(['queues' => function ($query) {
            $query->where('is_called', false);
        }])->get();
    }
}
