<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Invoice;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'invoice_number' => 'INV-' . $this->faker->unique()->numberBetween(1000, 9999),
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->email(),
            'customer_address' => $this->faker->address(),
            'status' => $this->faker->randomElement(['draft', 'sent', 'paid']),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'tax_rate' => $this->faker->randomFloat(2, 0, 20),
            // Calculated fields are set to 0 initially and updated later
            'subtotal' => 0,
            'tax_amount' => 0,
            'total_amount' => 0,
        ];
    }
}
