<?php

namespace App\Filament\Resources\TransactionCategories\Schemas;

use App\Models\TransactionCategory;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TransactionCategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->columnSpanFull(),
                TextEntry::make('transaction_type')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (TransactionCategory $record): bool => $record->trashed()),
            ]);
    }
}
