<?php

namespace App\Filament\Resources\Invoices\Tables;

use App\Models\Invoice;
use Filament\Tables\Table;
use App\Services\PdfService;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Exports\InvoiceExporter;
use App\Filament\Imports\InvoiceImporter;
use Filament\Tables\Filters\SelectFilter;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')->label('Inv #')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('customer_name')->label('Customer')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('customer_email')->label('Email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items')->badge()
                    ->tooltip(fn($record) => $record->items->pluck('description')->join(', ')),
                TextColumn::make('total_amount')->label('Total')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'gray'    => 'draft',
                        'warning' => 'sent',
                        'success' => 'paid',
                    ]),
                TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('Actions'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'sent'  => 'Sent',
                        'paid'  => 'Paid',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                // PDF Download Action
                Action::make('downloadPdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(fn(Invoice $record) => app(PdfService::class)->downloadInvoicePdf($record)),
            ])
            ->headerActions([
                ExportAction::make()->exporter(InvoiceExporter::class),
                ImportAction::make()->importer(InvoiceImporter::class),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    // // Bulk PDF Download
                    BulkAction::make('downloadPdfs')
                        ->label('Download PDFs')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(fn($records) => app(PdfService::class)->generateBulkInvoicePdfs($records)),
                ]),
            ]);
    }
}
