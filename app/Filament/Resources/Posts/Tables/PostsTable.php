<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Illuminate\Support\Facades\App;
use Filament\Actions\BulkActionGroup;
use App\Filament\Exports\PostExporter;
use App\Filament\Imports\PostImporter;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->limit(20)
                    ->tooltip(fn($record) => $record->slug)
                    ->copyable()
                    ->copyableState(fn($record) => $record->slug)
                    ->copyMessage('Slug copied!')
                    ->copyMessageDuration(1500),
                TextColumn::make('description')
                    ->words(3)
                    ->sortable()
                    ->searchable()
                    ->tooltip(fn($record) => $record->description),
                TextColumn::make('category.name')
                    ->searchable(),
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
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->headerActions([
                ExportAction::make('export.post')->exporter(PostExporter::class),
                ImportAction::make('import.post')->importer(PostImporter::class),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
