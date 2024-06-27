<?php

namespace App\Filament\Shop\Widgets;


use App\Models\Expense;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class SalesView extends BaseWidget
{
    protected static ?int $sort = -2;
    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        $sales = Sale::all()->sum('total_price');


        $expenses = Expense::all()->sum('total_amount');

        $purchases = Purchase::all()->sum('total_cost');


        return [
            Stat::make('Sales',   number_format((float)$sales, 0, '.', ','))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([1, 3, 3, 3, 3, 3, 1])
                ->color('success'),
            Stat::make('Expenses',   number_format((float)$expenses, 0, '.', ','))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([3, 1, 1, 1, 1, 1, 3])
                ->color('info'),
            Stat::make('Purchases',   number_format((float)$purchases, 0, '.', ','))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([1, 1, 3, 3, 3, 3, 1, 1])
                ->color('danger'),
        ];
    }
}
