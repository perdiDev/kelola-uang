<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Models\Item;
use App\Models\TransactionCategory;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        $type = request()->get('type');

        return $schema
            ->schema([
                Select::make('transaction_category_id')
                    ->label('Kategori')
                    ->options(
                        fn (Get $get) => TransactionCategory::where('transaction_type', $get('transaction_type'))
                            ->pluck('name', 'id')
                    )
                    ->reactive()

                    ->afterStateUpdated(function ($state, callable $set) {
                        $category = TransactionCategory::find($state);
                        // $set('transaction_type', $category?->transaction_type);
                    })
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->required()
                    ->live(),

                Select::make('item_id')
                    ->label(__('transaction.fields.item'))
                    ->options(
                        fn (Get $get): Collection => Item::query()
                            ->where('transaction_category_id', $get('transaction_category_id'))
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $item = Item::find($state);
                        $set('amount', $item?->item_price ?? 0);
                    }),

                DateTimePicker::make('transaction_date')
                    ->label(__('transaction.fields.transaction_date'))
                    ->native(false)
                    ->required(),

                TextInput::make('transaction_type')
                    ->default(request()->get('type'))
                    ->disabled()
                    ->dehydrated(),

                Textarea::make('description')
                    ->label(__('transaction.fields.description'))
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('amount')
                    ->label(__('transaction.fields.amount'))
                    ->required()
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('quantity')
                    ->label(__('transaction.fields.quantity'))
                    ->required()
                    ->numeric(),
            ]);
    }
}
