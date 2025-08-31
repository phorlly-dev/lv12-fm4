<?php

namespace App\Filament\Exports;

use App\Models\Invoice;
use Illuminate\Support\Number;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;
use Filament\Actions\Exports\Enums\ExportFormat;

class InvoiceExporter extends Exporter
{
    protected static ?string $model = Invoice::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('invoice_number')->label('Invoice #'),
            ExportColumn::make('customer_name')->label('Customer'),
            ExportColumn::make('customer_email')->label('Email'),
            ExportColumn::make('customer_address')->label('Address'),
            ExportColumn::make('status'),
            ExportColumn::make('due_date')->label('Due Date')
                ->getStateUsing(fn($record) => $record->due_date?->format('Y-m-d')),
            ExportColumn::make('subtotal'),
            ExportColumn::make('tax_rate'),
            ExportColumn::make('tax_amount'),
            ExportColumn::make('total_amount'),
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
        $body = 'Your invoice export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
