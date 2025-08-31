<?php

namespace App\Filament\Imports;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Number;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;

class InvoiceItemImporter extends Importer
{
    protected static ?string $model = InvoiceItem::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('invoice_number')->requiredMapping()
                ->label('Invoice #')
                ->rules(['required']),
            ImportColumn::make('description')->rules(['required']),
            ImportColumn::make('quantity')->rules(['required', 'integer']),
            ImportColumn::make('unit_price')->rules(['required', 'numeric']),
        ];
    }

    public function resolveRecord(): InvoiceItem
    {
        $invoiceNumber = $this->getColumnValue('invoice_number');

        $invoice = Invoice::where('invoice_number', $invoiceNumber)->first();

        $item = new InvoiceItem();
        if ($invoice) {
            $item->invoice()->associate($invoice);
        }

        return $item;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your invoice item import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
