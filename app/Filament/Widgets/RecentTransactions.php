<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentTransactions extends TableWidget
{
    protected static ?string $heading = 'Transaksi Terbaru';


    public function table(Table $table): Table
    {

        return $table
            ->paginated(false)
            ->query(
                Transaction::query()
                    ->latest('transaction_date')
                    ->limit(7)
            )
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('item.name')
                    ->label('Item')
                    ->weight('bold')
                    ->sortable()
                    ->searchable(),

                \Filament\Tables\Columns\TextColumn::make('amount')
                    ->label('Harga')
                    ->money('IDR', true)
                    ->searchable()
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('quantity')
                    ->label('Qty')
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR', true)
                    ->sortable(),

                \Filament\Tables\Columns\BadgeColumn::make('transaction_type')
                    ->label('Tipe')
                    ->formatStateUsing(fn ($state) => $state === 'income' ? 'Pemasukan' : 'Pengeluaran'
                    )
                    ->colors([
                        'success' => 'income',
                        'danger' => 'expense',
                    ])
                    ->icons([
                        'heroicon-o-arrow-up-circle' => 'income',
                        'heroicon-o-arrow-down-circle' => 'expense',
                    ])
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('transaction_type')
                    ->label('Filter Tipe')
                    ->options([
                        'income' => 'Pemasukan',
                        'expense' => 'Pengeluaran',
                    ]),
            ])
            ->filtersTriggerAction(fn ($action) => $action->button())
            ->recordUrl(fn ($record) => route('filament.dashboard.resources.transactions.edit', $record))
            ->striped();
    }

    protected int|string|array $columnSpan = 12;
}
