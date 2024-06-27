<?php

namespace App\Filament\Shop\Widgets;

use Carbon\Carbon;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class SalesOverview extends BaseWidget
{
    protected static ?string $heading = 'Profits per Week';
    protected static ?int $sort = -6;
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $totalSales = Sale::count();
        $totalSalesincome = Sale::sum('total_price');

        $currentWeekStart = Carbon::now()->startOfWeek();
        $weeklySales = Sale::where('created_at', '>=', $currentWeekStart)
            ->count();
        $weeklySalesincome = Sale::where('created_at', '>=', $currentWeekStart)
            ->sum('total_price');

        $currentMonthStart = Carbon::now()->startOfMonth();
        $monthlySales = Sale::where('created_at', '>=', $currentMonthStart)
            ->count();
        $monthlySalesIncome = Sale::where('created_at', '>=', $currentMonthStart)
            ->sum('total_price');

        $currentDay = Carbon::now()->startOfDay();
        $dailySales = Sale::where('created_at', '>=', $currentDay)
            ->count();
        $dailySalesIncome = Sale::where('created_at', '>=', $currentDay)
            ->sum('total_price');
        return [
            Stat::make('Total Sales', $totalSales)
                ->description('Total  ' . number_format((float)$totalSalesincome, 2, '.', ',')),
            Stat::make('Sales This Month', $monthlySales)
                ->description('Total ' . number_format((float)$monthlySalesIncome, 2, '.', ',')),
            Stat::make('Sales This Week', $weeklySales)
                ->description('Total ' . number_format((float)$weeklySalesincome, 2, '.', ',')),
            Stat::make('Sales Today', $dailySales)
                ->description('Total ' . number_format((float) $dailySalesIncome, 2, '.', ',')),
        ];
    }
}