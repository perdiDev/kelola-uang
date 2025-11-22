<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Models\Item;
use App\Models\TransactionCategory;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category')
                    ->label("Kategori")
                    ->options(TransactionCategory::query()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->live(),
                Select::make('item_id')
                    ->label(__('transaction.fields.item'))
                    ->options(
                        fn (Get $get): Collection
                        => Item::query()
                            ->where('transaction_category_id', $get('category'))
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->required(),
                DateTimePicker::make('transaction_date')
                    ->label(__('transaction.fields.transaction_date'))
                    ->native(false)
                    ->required(),
                Textarea::make('description')
                    ->label(__('transaction.fields.description'))
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('amount')
                    ->label(__('transaction.fields.amount'))
                    ->required()
                    ->numeric(),
                TextInput::make('quantity')
                    ->label(__('transaction.fields.quantity'))
                    ->required()
                    ->numeric(),
            ]);
    }
}
