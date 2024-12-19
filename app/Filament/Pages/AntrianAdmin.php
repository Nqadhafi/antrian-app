<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\Queue;
use Filament\Pages\Page;

class AntrianAdmin extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'Kelola Antrian';
    protected static string $view = 'filament.pages.antrian-admin';

    public function callNext($categoryId)
    {
        $queue = Queue::where('category_id', $categoryId)
            ->where('is_called', false)
            ->orderBy('id')
            ->first();

        if ($queue) {
            $queue->update(['is_called' => true]);
            $this->notify('success', "Antrian {$queue->number} telah dipanggil!");
        } else {
            $this->notify('warning', 'Tidak ada antrian yang tersedia.');
        }
    }

    public function recallLast($categoryId)
    {
        $queue = Queue::where('category_id', $categoryId)
            ->where('is_called', true)
            ->latest()
            ->first();

        if ($queue) {
            $this->notify('success', "Antrian {$queue->number} dipanggil ulang!");
        } else {
            $this->notify('warning', 'Tidak ada antrian yang dapat dipanggil ulang.');
        }
    }

    public function resetQueue($categoryId)
    {
        Queue::where('category_id', $categoryId)->delete();
        $this->notify('success', 'Nomor antrian telah direset.');
    }

    public function getCategoriesProperty()
    {
        return Category::withCount(['queues' => function ($query) {
            $query->where('is_called', false);
        }])->get();
    }
}
