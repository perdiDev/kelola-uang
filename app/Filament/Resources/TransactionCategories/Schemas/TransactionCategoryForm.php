<?php

namespace App\Filament\Resources\TransactionCategories\Schemas;

use App\Enums\TransactionType;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class TransactionCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('category.fields.name'))
                    ->required()
                    ->minLength(3),
                Select::make('transaction_type')
                    ->label(__('category.fields.transaction_type'))
                    ->options(TransactionType::class)
                    ->native(false)
                    ->required(),
            ]);
    }
}
