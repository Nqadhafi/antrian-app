<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoResource\Pages;
use App\Filament\Resources\VideoResource\RelationManagers;
use App\Models\Video;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->label('Judul Video'),
                Forms\Components\FileUpload::make('path')
                    ->required()
                    ->label('File Video')
                    ->directory('videos') // Simpan file di storage/videos
                    ->visibility('public')
                    ->helperText('Unggah file video dengan format .mp4'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Video')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('path')
                    ->label('Path File')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(), // Tombol Edit
                Tables\Actions\DeleteAction::make(), // Tombol Delete
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}