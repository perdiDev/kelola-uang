<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Forms\Components\Select;
use Filament\Widgets\ChartWidget;

class CategoryIncomeChart extends ChartWidget
{
    protected ?string $heading = 'Pemasukan Berdasarkan Kategori';

    public ?string $filter = 'this_month'; // default

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
        $query = Transaction::where('transaction_type', 'income')->with('category');

        switch ($this->filter) {
            case 'today':
                $query->whereDate('transaction_date', now());
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

        $incomes = $query->get()->groupBy('transaction_category_id');

        $labels = $incomes->map(
            fn ($group) => $group->first()->category->name
        )->values();

        $data = $incomes->map(
            fn ($group) => $group->sum(fn ($item) => $item->amount * $item->quantity)
        )->values();

        $greens = [
            'rgba(0, 200, 83, 0.8)',
            'rgba(27, 94, 32, 0.8)',
            'rgba(76, 175, 80, 0.8)',
            'rgba(139, 195, 74, 0.8)',
            'rgba(56, 142, 60, 0.8)',
            'rgba(102, 187, 106, 0.8)',
            'rgba(129, 199, 132, 0.8)',
        ];

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $data,
                    'backgroundColor' => $labels->map(
                        fn ($_, $i) => $greens[$i % count($greens)]
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
