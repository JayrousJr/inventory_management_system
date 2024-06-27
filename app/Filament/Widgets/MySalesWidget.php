<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Sale;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class MySalesWidget extends ChartWidget
{
    protected static ?string $heading = 'My Sales Per Week';
    protected static bool $isLazy = false;
    protected static ?int $sort = -6;
    protected int | string | array $columnSpan = 'full';
    protected function getData(): array
    {
        // $getInfo =  Trend::query(Sale::where('shop_id', Auth::user()->shop_id))->between(now()->startOfWeek(), now()->endOfWeek())->perDay()
        // ->sum('paid_amount');
        $getInfo =  Trend::query(Sale::where('saler_name', Auth::user()->name))->between(now()->startOfWeek(), now()->endOfWeek())->perDay()
            ->count();
        $labels = $getInfo->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('D'));
        $data =  $getInfo->map(fn (TrendValue $value) => $value->aggregate);

        return [

            'labels' => $labels,

            'datasets' => [
                [
                    'label' => 'Sale(s)',
                    'data' => $data,
                    'fill' => [
                        'target' => 'origin',
                        'below' => 'rgba(54, 162, 235, 0.2)',
                        'above' => 'rgba(54, 162, 235, 0.2)',
                    ],
                    'borderColor' => 'rgba(54, 162, 235, 0.7)',
                    'tension' => 0.5,
                ],
            ],

        ];
    }


    protected function getType(): string
    {
        return 'line';
    }
}