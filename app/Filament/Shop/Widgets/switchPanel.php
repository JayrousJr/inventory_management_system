<?php

namespace App\Filament\Shop\Widgets;

use Filament\Widgets\Widget;

class switchPanel extends Widget
{
    protected static ?string $heading = 'Sales In a year';
    protected static ?int $sort = -7;
    protected int | string | array $columnSpan = 'full';

    protected static bool $isLazy = false;
    protected static string $view = 'filament.shop.widgets.switch-panel';
}
