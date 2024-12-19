<?php

namespace App\Filament\Resources\QueueResource\Pages;

use App\Filament\Resources\QueueResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Events\QueueUpdated;
use App\Models\Queue;
// use App\Filament\Widgets\EchoScript; // Hapus baris ini

class ListQueues extends ListRecords
{
    protected static string $resource = QueueResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('callNext')
                ->label('Panggil Selanjutnya')
                ->action(function (Queue $record) {
                    // Implementasi aksi
                }),
        ];
    }

    protected function getWidgets(): array
    {
        return [
            // EchoScript::class, // Hapus baris ini
        ];
    }
}
