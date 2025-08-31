<?php

namespace App\Models;

use function Spatie\LaravelPdf\Support\pdf;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_name',
        'customer_email',
        'customer_address',
        'status',
        'due_date',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total_amount',
    ];

    protected $casts = [
        'due_date'     => 'date',
        'subtotal'     => 'decimal:2',
        'tax_rate'     => 'decimal:2',
        'tax_amount'   => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // Relationship with invoice items
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // Calculate totals automatically
    public function calculateTotals($save = true)
    {
        $this->subtotal     = $this->items->sum('total_price');
        $this->tax_amount   = $this->subtotal * ($this->tax_rate / 100);
        $this->total_amount = $this->subtotal + $this->tax_amount;

        if ($save) {
            $this->saveQuietly(); // ✅ avoids triggering saved() again
        }
    }

    // Boot method to auto-calculate totals
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($invoice) {
            if ($invoice->items()->exists()) {
                $invoice->calculateTotals(save: false); // ✅ calculate but don’t resave here
            }
        });
    }

    public static function download(Invoice $invoice)
    {
        return pdf()->view('pdf.invoice2', compact('invoice'))
            ->format('A4')
            ->name("invoice-{$invoice->invoice_number}-" . now()->format('is') . ".pdf")
            ->download();
    }
}
