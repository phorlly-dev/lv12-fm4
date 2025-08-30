<?php

namespace App\Filament\Resources\Categories\Schemas;

use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true) // Updates when focus leaves the field (you can use onChange for real-time)
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', Str::slug($state));
                    }),
                TextInput::make('slug')
                    ->disabled() // makes the field read-only in the form
                    ->dehydrated() // ensures the value is still sent to the backend for saving
                    ->required()
                    ->unique(ignorable: fn($record) => $record),
            ]);
    }
}
