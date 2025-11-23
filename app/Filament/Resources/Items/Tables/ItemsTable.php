<?php

namespace App\Filament\Resources\Items\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('item.fields.name'))
                    ->searchable(),
                TextColumn::make('transactionCategory.name')
                    ->label(__('item.fields.transaction_category_id'))
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('item_price')
                    ->label(__('item.fields.item_price'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                // Filter kategori (relasi)
                \Filament\Tables\Filters\SelectFilter::make('transaction_category_id')
                    ->label('Kategori')
                    ->relationship('transactionCategory', 'name')
                    ->searchable(),

                // Filter rentang harga
                \Filament\Tables\Filters\Filter::make('rentang_harga')
                    ->label('Rentang Harga')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('min')
                            ->numeric()
                            ->label('Harga terendah'),
                        \Filament\Forms\Components\TextInput::make('max')
                            ->numeric()
                            ->label('Harga tertinggi'),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['min'] ?? null, fn ($q, $min) => $q->where('item_price', '>=', $min)
                        )
                        ->when($data['max'] ?? null, fn ($q, $max) => $q->where('item_price', '<=', $max)
                        )
                    ),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            
            ->filtersTriggerAction(fn ($action) => $action->button())

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
