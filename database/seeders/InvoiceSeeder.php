<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First create some Invoices
        $invoices = Invoice::factory(1000)->create();

        // Then create posts assigned to random Invoices
        InvoiceItem::factory(500)->create([
            'invoice_id' => fn() => $invoices->random()->id,
        ]);
    }
}
