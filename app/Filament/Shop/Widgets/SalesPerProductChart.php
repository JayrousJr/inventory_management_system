<?php

namespace App\Filament\Shop\Widgets;


use App\Models\Sale;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class SalesPerProductChart extends ChartWidget
{
    protected static ?string $heading = 'Sales Per Sales Person';
    // protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 6;
    protected static bool $isLazy = false;
    protected static ?string $maxHeight = '250px';
    protected function getData(): array
    {
        $sales = Sale::select('saler_name', 'id', 'shop_id')
            // ->where('shop_id', Auth::user()->shop_id)
            ->get()
            ->groupBy(function ($brand) {
                return $brand->saler_name;
            });

        $salesData = [];

        foreach ($sales as $sale => $groupSales) {
            $salesData[$sale] = $groupSales->count();
        }

        $labels = array_keys($salesData);
        $data = array_values($salesData);
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Sales',
                    'data' => $data,
                    'backgroundColor' =>  [
                        // 'rgb(233,114,77)',
                        // 'rgb(215,215,39)',
                        // 'rgb(146,202,209)',
                        // 'rgb(121,204,179)',
                        // 'rgb(134,134,134)',
                        'rgb(217, 119, 6)', //amber
                        'rgb(22, 163, 74)', //green
                        'rgb(71, 85, 105)', //slate'
                        'rgb(220, 38, 38)', //red
                        'rgb(147, 51, 234)', //purple
                        'rgb(192, 38, 211)', //fuchsia
                        'rgb(219, 39, 119)', //pink
                        'rgb(234, 88, 12)', //orange
                        'rgb(225, 29, 72)', //rose
                        'rgb(71, 85, 105)', //slate'
                        'rgb(8, 145, 178)', //cyan
                        'rgb(2, 132, 199)', //sky
                        'rgb(37, 99, 235)', //blue
                        'rgb(79, 70, 229)', //indigo
                        'rgb(220, 38, 38)', //red
                        'rgb(234, 88, 12)', //orange
                        'rgb(217, 119, 6)', //amber
                        'rgb(202, 138, 4)', //yellow
                        'rgb(101, 163, 13)', //lime
                        'rgb(22, 163, 74)', //green
                        'rgb(5, 150, 105)', //emerald
                        'rgb(13, 148, 136)', //teal

                        'rgb(124, 58, 237)', //violet
                        'rgb(147, 51, 234)', //purple
                        'rgb(192, 38, 211)', //fuchsia
                        'rgb(219, 39, 119)', //pink
                        'rgb(225, 29, 72)', //rose
                        'rgb(71, 85, 105)', //slate 'rgb(75, 85, 99)', //gray
                        'rgb(82, 82, 91)', //zinc
                        'rgb(115, 115, 115)', //neutral
                    ],
                    'hoverOffset' => 4
                ],
            ],
        ];
    }
    protected function getType(): string
    {
        return 'doughnut';
    }
}
