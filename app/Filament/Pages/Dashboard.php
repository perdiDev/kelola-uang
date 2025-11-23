<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CategoryExpenseChart;
use App\Filament\Widgets\CategoryIncomeChart;
use App\Filament\Widgets\FinancialStatsWidget;
use App\Filament\Widgets\RecentTransactions;
use App\Filament\Widgets\StatsChart;
use App\Filament\Widgets\TopItemsTableWidget;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'Dashboard';

    public function getColumns(): int | array
    {
        return 12;
    }

    public function getWidgets(): array
    {
        return [
            FinancialStatsWidget::class,
            CategoryIncomeChart::class,
            CategoryExpenseChart::class,
            TopItemsTableWidget::class,
            StatsChart::class,
            RecentTransactions::class,
        ];
    }
}
