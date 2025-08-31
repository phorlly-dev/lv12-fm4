<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Invoice Information')
                    ->schema([
                        TextInput::make('invoice_number')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'sent' => 'Sent',
                                'paid' => 'Paid',
                            ])
                            ->required(),
                        DatePicker::make('due_date')
                            ->required(),
                        TextInput::make('tax_rate')
                            ->numeric()
                            ->suffix('%')
                            ->default(0),
                    ])
                    ->columns(2),

                Section::make('Customer Information')
                    ->schema([
                        TextInput::make('customer_name')
                            ->required(),
                        TextInput::make('customer_email')
                            ->email()
                            ->required(),
                        Textarea::make('customer_address')
                            ->required()
                            ->rows(3),
                    ])
                    ->columns(2),

                Section::make('Invoice Items')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                TextInput::make('description')
                                    ->required()
                                    ->columnSpan(2),
                                TextInput::make('quantity')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, Set $set, Get $get) =>
                                    $set('total_price', $state * $get('unit_price'))),
                                TextInput::make('unit_price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, Set $set, Get $get) =>
                                    $set('total_price', $state * $get('quantity'))),
                                TextInput::make('total_price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->disabled()
                                    ->dehydrated(false),
                            ])
                            ->columns(4)
                            ->defaultItems(1)
                            ->addActionLabel('Add Item')
                            ->reorderableWithButtons()
                            ->collapsible(),
                    ]),
            ]);
    }
}
