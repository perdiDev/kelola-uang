<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinancialStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $now = Carbon::now();

        // Semua pemasukan
        $totalIncome = Transaction::where('transaction_type', 'income')
            ->get()
            ->sum(fn ($t) => $t->amount * $t->quantity);

        // Semua pengeluaran
        $totalExpense = Transaction::where('transaction_type', 'expense')
            ->get()
            ->sum(fn ($t) => $t->amount * $t->quantity);

        // Bulan ini
        $monthlyIncome = Transaction::where('transaction_type', 'income')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->get()
            ->sum(fn ($t) => $t->amount * $t->quantity);

        $monthlyExpense = Transaction::where('transaction_type', 'expense')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->get()
            ->sum(fn ($t) => $t->amount * $t->quantity);

        return [
            Stat::make('Dompet', 'Rp '.number_format($totalIncome - $totalExpense, 0, ',', '.'))
                ->description('Saldo total tersedia')
                ->descriptionColor($totalIncome >= $totalExpense ? 'success' : 'danger')
                ->icon('heroicon-o-wallet'),

            Stat::make('Pemasukan', 'Rp '.number_format($monthlyIncome, 0, ',', '.'))
                ->description('Bulan ini')
                ->descriptionColor('success')
                ->icon('heroicon-o-arrow-up-circle'),

            Stat::make('Pengeluaran', 'Rp '.number_format($monthlyExpense, 0, ',', '.'))
                ->description('Bulan ini')
                ->descriptionColor('danger')
                ->icon('heroicon-o-arrow-down-circle'),

            Stat::make('Keuntungan', 'Rp '.number_format($monthlyIncome - $monthlyExpense, 0, ',', '.'))
                ->description('Bulan ini')
                ->descriptionColor(($monthlyIncome - $monthlyExpense) >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-chart-bar'),

        ];
    }

    public function getColumns(): int|array
    {
        return [
            'default' => 2,
            'lg' => 4,
        ];
    }
}
