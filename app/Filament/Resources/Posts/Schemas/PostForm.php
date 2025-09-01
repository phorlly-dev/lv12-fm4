<?php

namespace App\Filament\Resources\Posts\Schemas;

use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->live(onBlur: true)
                    ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state)))
                    ->required(),
                TextInput::make('slug')
                    ->disabled() // makes the field read-only in the form
                    ->dehydrated() // ensures the value is still sent to the backend for saving
                    ->required()
                    ->unique(ignorable: fn($record) => $record),
                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
            ]);
    }
}
