<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('item.name')
                    ->label(__('transaction.fields.item'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('transaction_type')
                    ->label('Tipe')
                    ->sortable()
                    ->badge()
                    ->searchable()
                    ->colors([
                        'success' => 'income',
                        'danger' => 'expense',
                    ])
                    ->formatStateUsing(fn ($state) => $state === 'income' ? 'Pemasukan' : 'Pengeluaran'),

                TextColumn::make('transaction_date')
                    ->label(__('transaction.fields.transaction_date'))
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('amount')
                    ->label(__('transaction.fields.amount'))
                    ->searchable()
                    ->numeric()
                    ->sortable(),

                TextColumn::make('quantity')
                    ->label(__('transaction.fields.quantity'))
                    ->numeric()
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->numeric()
                    ->sortable()
                    ->money('IDR'), // kalau mau format uang otomatis

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
                \Filament\Tables\Filters\Filter::make('hari_ini')
                    ->label('Hari Ini')
                    ->query(fn ($query) => $query->whereDate('transaction_date', now()->toDateString())
                    ),

                \Filament\Tables\Filters\Filter::make('7_hari_terakhir')
                    ->label('7 Hari Terakhir')
                    ->query(fn ($query) => $query->where('transaction_date', '>=', now()->subDays(7))
                    ),

                \Filament\Tables\Filters\Filter::make('30_hari_terakhir')
                    ->label('30 Hari Terakhir')
                    ->query(fn ($query) => $query->where('transaction_date', '>=', now()->subDays(30))
                    ),
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
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['min'] ?? null, fn ($q, $min) => $q->where('amount', '>=', $min)
                            )
                            ->when($data['max'] ?? null, fn ($q, $max) => $q->where('amount', '<=', $max)
                            );
                    }),
            ])
            ->searchable()
            ->recordActions([
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
