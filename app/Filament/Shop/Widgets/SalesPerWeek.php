<?php

namespace App\Filament\Shop\Widgets;


use Carbon\Carbon;
use App\Models\Sale;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class SalesPerWeek extends ChartWidget
{
    protected static ?string $heading = 'Sales per Week';
    protected static ?int $sort = 0;
    protected static bool $isLazy = false;
    protected static ?string $maxHeight = '250px';
    protected function getData(): array
    {
        // $getInfo =  Trend::query(Sale::where('shop_id', Auth::user()->shop_id))->between(now()->startOfWeek(), now()->endOfWeek())->perDay()
        // ->sum('paid_amount');
        $getInfo =  Trend::model(Sale::class)
            ->between(now()->startOfWeek(), now()->endOfWeek())
            ->perDay()
            ->sum('paid_amount');
        $labels = $getInfo->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('D'));
        $data =  $getInfo->map(fn (TrendValue $value) => $value->aggregate);

        return [

            'labels' => $labels,
            'datasets' => [
                [
                    // 'data' => ['Monday', 'Tuesday', 'Wednesday', 'Thursay', 'Friday', 'Saturday', 'Sunday'],

                    'label' => 'Sales',
                    'data' => $data,
                    'backgroundColor' =>  [
                        'rgb(37, 99, 235)', //blue
                        'rgb(202, 138, 4)', //yellow
                        'rgb(219, 39, 119)', //pink
                        'rgb(71, 85, 105)', //slate'
                        'rgb(220, 38, 38)', //red
                        'rgb(8, 145, 178)', //cyan
                        'rgb(13, 148, 136)', //teal
                        'rgb(225, 29, 72)', //rose
                        'rgb(192, 38, 211)', //fuchsia
                        'rgb(22, 163, 74)', //green
                        'rgb(71, 85, 105)', //slate'
                        'rgb(2, 132, 199)', //sky
                        'rgb(79, 70, 229)', //indigo
                        'rgb(217, 119, 6)', //amber
                        'rgb(101, 163, 13)', //lime
                        'rgb(22, 163, 74)', //green
                        'rgb(234, 88, 12)', //orange
                        'rgb(147, 51, 234)', //purple
                        'rgb(5, 150, 105)', //emerald


                    ],
                    'borderColor' =>  [
                        // 'rgb(37, 99, 235)', //blue
                        // 'rgb(202, 138, 4)', //yellow
                        // 'rgb(147, 51, 234)', //purple
                        // 'rgb(79, 70, 229)', //indigo
                        // 'rgb(220, 38, 38)', //red
                        // 'rgb(192, 38, 211)', //fuchsia
                        // 'rgb(71, 85, 105)', //slate'
                        // 'rgb(22, 163, 74)', //green
                        // 'rgb(219, 39, 119)', //pink
                        // 'rgb(225, 29, 72)', //rose
                        // 'rgb(71, 85, 105)', //slate'
                        // 'rgb(8, 145, 178)', //cyan
                        // 'rgb(2, 132, 199)', //sky
                        // 'rgb(234, 88, 12)', //orange
                        // 'rgb(217, 119, 6)', //amber
                        // 'rgb(101, 163, 13)', //lime
                        // 'rgb(22, 163, 74)', //green
                        // 'rgb(5, 150, 105)', //emerald
                        // 'rgb(13, 148, 136)', //teal
                    ],
                    'borderWidth' =>  1
                ],

            ],

        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
