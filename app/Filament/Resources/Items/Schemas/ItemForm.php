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
                    ->label(__('item.fields.transaction_category_id'))
                    ->relationship(name: 'transactionCategory', titleAttribute: 'name')
                    ->native(false)
                    ->required(),
                TextInput::make('name')
                    ->label(__('item.fields.name'))
                    ->required(),
                TextInput::make('item_price')
                    ->label(__('item.fields.item_price'))
                    ->required()
                    ->numeric(),
            ]);
    }
}
