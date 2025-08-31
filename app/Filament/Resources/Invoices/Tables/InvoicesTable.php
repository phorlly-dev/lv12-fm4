<?php

namespace App\Filament\Resources\Invoices\Tables;

use App\Models\Invoice;
use App\Services\PdfService;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use ZipArchive;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('customer_name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('customer_email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items'),
                TextColumn::make('total_amount')
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
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    // // Bulk PDF Download
                    // BulkAction::make('downloadPdfs')
                    //     ->label('Download PDFs')
                    //     ->icon('heroicon-o-document-arrow-down')
                    //     ->action(fn($records) => app(PdfService::class)->generateBulkInvoicePdfs($records)),
                ]),
            ]);
    }
}
