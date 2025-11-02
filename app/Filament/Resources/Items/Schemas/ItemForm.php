<?php

namespace App\Filament\Resources\Items\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('transaction_category_id')
                    ->label('Category')
                    ->relationship(name: 'transactionCategory', titleAttribute: 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('item_price')
                    ->required()
                    ->numeric(),
            ]);
    }
}
