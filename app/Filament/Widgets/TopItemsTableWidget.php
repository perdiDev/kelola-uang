<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopItemsTableWidget extends BaseWidget
{
    protected static ?string $heading = 'Top Item Transaksi';

    protected int|string|array $columnSpan = 4;

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false);
    }

    protected function getTableQuery(): Builder
    {
        return Transaction::selectRaw('MIN(id) as id, item_id')
            ->selectRaw('SUM(quantity) as total')
            ->selectRaw("SUM(CASE WHEN transaction_type='income' THEN quantity ELSE 0 END) as income")
            ->selectRaw("SUM(CASE WHEN transaction_type='expense' THEN quantity ELSE 0 END) as expense")
            ->with('item')
            ->groupBy('item_id')
            ->orderByDesc('total')
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('item.name')
                ->label('Item')
                ->formatStateUsing(function ($state, $record) {
                    static $icons = ['🥇', '🥈', '🥉', '🎖️', '🏅'];
                    static $i = 0;

                    return $icons[$i].' '.$state;
                })
                ->weight('bold'),

            Tables\Columns\TextColumn::make('total')
                ->label('Total')
                ->alignRight()
                ->badge()
                ->color('primary'),

            Tables\Columns\TextColumn::make('income')
                ->label('Inc')
                ->alignRight()
                ->badge()
                ->color('success'),

            Tables\Columns\TextColumn::make('expense')
                ->label('Exp')
                ->alignRight()
                ->badge()
                ->color('danger'),
        ];
    }

    protected function getDefaultTablePaginationPageSize(): int
    {
        return 5;
    }
}
