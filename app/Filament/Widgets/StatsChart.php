<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class StatsChart extends ChartWidget
{
    protected ?string $heading = 'Statistik Keuangan';

    public ?string $filter = '7days';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            '7days' => '7 Hari Terakhir',
            '30days' => '30 Hari Terakhir',
            'this_month' => 'Bulan Ini',
            'custom' => 'Custom Range',
        ];
    }

    protected function getData(): array
    {
        $query = Transaction::query();

        switch ($this->filter) {
            case 'today':
                $query->whereDate('transaction_date', now()->toDateString());
                break;

            case '7days':
                $query->where('transaction_date', '>=', now()->subDays(7));
                break;

            case '30days':
                $query->where('transaction_date', '>=', now()->subDays(30));
                break;

            case 'this_month':
                $query->whereMonth('transaction_date', now()->month)
                    ->whereYear('transaction_date', now()->year);
                break;
        }

        $data = $query
            ->select(
                DB::raw('DATE(transaction_date) as date'),
                DB::raw("SUM(CASE WHEN transaction_type = 'income' THEN amount * quantity ELSE 0 END) as income"),
                DB::raw("SUM(CASE WHEN transaction_type = 'expense' THEN amount * quantity ELSE 0 END) as expense")
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $data->pluck('income'),
                    'borderColor' => 'rgb(0, 200, 83)',
                    'backgroundColor' => 'rgba(0, 200, 83, 0.4)',
                    // 'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => $data->pluck('expense'),
                    'borderColor' => 'rgb(244, 67, 54)',
                    'backgroundColor' => 'rgba(244, 67, 54, 0.4)',
                    // 'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Keuntungan',
                    'data' => $data->map(fn ($row) => $row->income - $row->expense),
                    'borderColor' => 'rgb(33, 150, 243)',
                    'backgroundColor' => 'rgba(33, 150, 243, 0.4)',
                    // 'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $data->pluck('date'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected int|string|array $columnSpan = 12;
}
