<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CategoryExpenseChart extends ChartWidget
{
    protected ?string $heading = 'Pengeluaran Berdasarkan Kategori';

    public ?string $filter = 'this_month'; // Default: bulan ini

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            '7days' => '7 Hari Terakhir',
            '30days' => '30 Hari Terakhir',
            'this_month' => 'Bulan Ini',
            'last_month' => 'Bulan Lalu',
        ];
    }

    protected function getData(): array
    {
        $query = Transaction::where('transaction_type', 'expense')->with('category');

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

            case 'last_month':
                $query->whereMonth('transaction_date', now()->subMonth()->month)
                      ->whereYear('transaction_date', now()->subMonth()->year);
                break;
        }

        $expenses = $query
            ->get()
            ->groupBy('transaction_category_id');

        $labels = $expenses->map(
            fn ($group) => $group->first()->category->name
        )->values();

        $data = $expenses->map(
            fn ($group) => $group->sum(fn ($item) => $item->amount * $item->quantity)
        )->values();

        $reds = [
            'rgba(244, 67, 54, 0.8)',
            'rgba(183, 28, 28, 0.8)',
            'rgba(229, 57, 53, 0.8)',
            'rgba(255, 82, 82, 0.8)',
            'rgba(211, 47, 47, 0.8)',
            'rgba(239, 83, 80, 0.8)',
            'rgba(255, 138, 128, 0.8)',
        ];

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Pengeluaran',
                    'data' => $data,
                    'backgroundColor' => $labels->map(
                        fn ($_, $i) => $reds[$i % count($reds)]
                    ),
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                    'hoverOffset' => 8,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected int|string|array $columnSpan = 4;
}
