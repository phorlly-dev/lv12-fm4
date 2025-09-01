<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->limit(20)
                    ->tooltip(fn($record) => $record->slug)
                    ->copyable()
                    ->copyableState(fn($record) => $record->slug)
                    ->copyMessage('Slug copied!')
                    ->copyMessageDuration(1500),
                TextColumn::make('posts_count')
                    ->counts('posts')
                    ->label('Posts')->badge()
                    ->tooltip(fn($record) => $record->posts->pluck('title')->join(', ')),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
