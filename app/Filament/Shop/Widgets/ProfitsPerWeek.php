<?php

namespace App\Filament\Shop\Widgets;


use Carbon\Carbon;
use App\Models\Sale;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class ProfitsPerWeek extends ChartWidget
{
    protected static ?string $heading = 'Profits per Week';
    protected static ?int $sort = -1;
    protected static bool $isLazy = false;
    protected static ?string $maxHeight = '250px';
    protected function getData(): array
    {
        $getInfo =  Trend::model(Sale::class)->between(now()->startOfWeek(), now()->endOfWeek())->perDay()
            ->sum('profit');
        $labels = $getInfo->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('D'));
        $data =  $getInfo->map(fn (TrendValue $value) => $value->aggregate);

        return [
            'labels' => $labels,

            'datasets' => [
                [
                    'label' => 'Profit',
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
