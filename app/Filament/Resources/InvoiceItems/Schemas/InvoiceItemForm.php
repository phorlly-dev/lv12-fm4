<?php

namespace App\Filament\Resources\InvoiceItems\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvoiceItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('invoice_id')
                    ->required()
                    ->numeric(),
                TextInput::make('description')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                TextInput::make('unit_price')
                    ->required()
                    ->numeric(),
                TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
