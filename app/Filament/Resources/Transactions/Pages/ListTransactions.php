<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('addIncome')
                ->label('Tambah Pemasukan')
                ->color('success')
                ->icon('heroicon-m-arrow-up-circle')
                ->url(static::getResource()::getUrl('create', ['type' => 'income'])),

            Actions\Action::make('addExpense')
                ->label('Tambah Pengeluaran')
                ->color('danger')
                ->icon('heroicon-m-arrow-down-circle')
                ->url(static::getResource()::getUrl('create', ['type' => 'expense'])),
        ];
    }
}
