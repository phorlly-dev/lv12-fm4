<?php

namespace App\Filament\Exports;

use App\Models\InvoiceItem;
use Illuminate\Support\Number;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;
use Filament\Actions\Exports\Enums\ExportFormat;

class InvoiceItemExporter extends Exporter
{
    protected static ?string $model = InvoiceItem::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('invoice.invoice_number')->label('Invoice #')
                ->getStateUsing(fn($record) => $record->invoice?->invoice_number),
            ExportColumn::make('invoice.customer_name')->label('Customer')
                ->getStateUsing(fn($record) => $record->invoice?->customer_name),
            ExportColumn::make('description')->label('Item'),
            ExportColumn::make('quantity'),
            ExportColumn::make('unit_price'),
            ExportColumn::make('total_price'),
            ExportColumn::make('created_at')
                ->getStateUsing(fn($record) => $record->created_at?->format('Y-m-d H:i:s')),
        ];
    }

    public function getFormats(): array
    {
        return [
            ExportFormat::Csv,
            ExportFormat::Xlsx,
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your invoice item export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
