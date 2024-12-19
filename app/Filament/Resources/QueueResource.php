<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QueueResource\Pages;
use App\Models\Queue;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
// use App\Events\QueueUpdated; // Hapus baris ini

class QueueResource extends Resource
{
    protected static ?string $model = Queue::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Kategori')
                    ->required(),
                TextInput::make('number')
                    ->label('Nomor Antrian')
                    ->required(),
                Toggle::make('is_called')
                    ->label('Dipanggil'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->label('Nomor Antrian'),
                TextColumn::make('category.name')->label('Kategori'),
                BooleanColumn::make('is_called')->label('Telah Dipanggil'),
            ])
            ->actions([
                Action::make('callNext')
                    ->label('Panggil Selanjutnya')
                    ->icon('heroicon-o-play')
                    ->action(function (Queue $record) {
                        // Implementasi aksi tanpa emit event
                        $nextQueue = Queue::where('category_id', $record->category_id)
                            ->where('is_called', false)
                            ->orderBy('id')
                            ->first();

                        if ($nextQueue) {
                            $nextQueue->update(['is_called' => true]);

                            $remainingQueues = Queue::where('category_id', $nextQueue->category_id)
                                ->where('is_called', false)
                                ->count();

                            // Hapus emit event
                            // event(new QueueUpdated($nextQueue->category_id, $remainingQueues));

                            Notification::make()
                                ->title("Antrian {$nextQueue->number} telah dipanggil!")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title("Tidak ada antrian yang tersedia.")
                                ->warning()
                                ->send();
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQueues::route('/'),
            'create' => Pages\CreateQueue::route('/create'),
            'edit' => Pages\EditQueue::route('/{record}/edit'),
        ];
    }
}
