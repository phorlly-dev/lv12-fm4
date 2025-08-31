<?php

namespace App\Filament\Imports;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Number;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;

class PostImporter extends Importer
{
    protected static ?string $model = Post::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('slug')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('description'),
            ImportColumn::make('category')
                ->requiredMapping()
                ->relationship('category', 'name')
                ->rules(['required']),
        ];
    }

    public function resolveRecord(): Post
    {
        return new Post();
    }

    public function beforeSave(): void
    {
        $categoryName = $this->getColumnValue('category');

        if ($categoryName) {
            $category = Category::firstOrCreate(['name' => $categoryName]);
            $this->record->category()->associate($category);
        }
    }


    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your post import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
