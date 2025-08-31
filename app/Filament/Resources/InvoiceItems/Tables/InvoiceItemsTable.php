<?php

namespace App\Filament\Resources\InvoiceItems\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Exports\InvoiceItemExporter;
use App\Filament\Imports\InvoiceItemImporter;

class InvoiceItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice.invoice_number')->label('Invoice #')
                    ->sortable(),
                TextColumn::make('invoice.customer_name')->label('Customer')
                    ->sortable(),
                TextColumn::make('description')->label('Item')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unit_price')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('total_price')->label('Total')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->getStateUsing(fn($record) => $record->created_at?->format('Y-m-d H:i:s'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->getStateUsing(fn($record) => $record->updated_at?->format('Y-m-d H:i:s'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ImportAction::make()->importer(InvoiceItemImporter::class),
                ExportAction::make()->exporter(InvoiceItemExporter::class),
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
