<?php

namespace App\Filament\Shop\Widgets;

use App\Models\Debt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DebtsView extends BaseWidget
{
    protected static bool $isLazy = false;
    protected static ?string $maxHeight = '280px';
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $sales = Debt::all()
            ->where('source_name', 'sale')
            ->sum('remaining_amount');
        $expenses = Debt::all()
            ->where('source_name', 'expenses')
            ->sum('remaining_amount');
        $purchases = Debt::all()
            ->where('source_name', 'purchase')
            ->sum('remaining_amount');
        $stores = Debt::all()
            ->where('source_name', 'store')
            ->sum('remaining_amount');
        return [
            Stat::make('Unpaid Sales',   number_format((float)$sales, 0, '.', ','))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([1, 3, 3, 3, 3, 3, 1])
                ->color('success'),
            Stat::make('Unpaid Expenses',   number_format((float)$expenses, 0, '.', ','))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([3, 1, 1, 1, 1, 1, 3])
                ->color('info'),
            Stat::make('Unpaid Purchases',   number_format((float)$purchases, 0, '.', ','))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([1, 1, 3, 3, 3, 3, 1, 1])
                ->color('danger'),
            Stat::make('Unpaid Store Products',   number_format((float)$stores, 0, '.', ','))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([1, 3, 3, 3, 3, 3, 1])
                ->color('success'),

        ];
    }
}
