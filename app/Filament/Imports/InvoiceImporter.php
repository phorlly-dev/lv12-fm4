<?php

namespace App\Filament\Imports;

use App\Models\Invoice;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class InvoiceImporter extends Importer
{
    protected static ?string $model = Invoice::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('invoice_number')->requiredMapping()->rules(['required']),
            ImportColumn::make('customer_name')->requiredMapping()->rules(['required']),
            ImportColumn::make('customer_email')->requiredMapping()->rules(['required', 'email']),
            ImportColumn::make('customer_address')->requiredMapping(),
            ImportColumn::make('status'),
            ImportColumn::make('due_date')->rules(['date']),
            ImportColumn::make('tax_rate'),
        ];
    }

    public function resolveRecord(): Invoice
    {
        return new Invoice();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your invoice import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
