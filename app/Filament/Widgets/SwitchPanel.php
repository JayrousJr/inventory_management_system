<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class SwitchPanel extends Widget
{
    protected static ?string $heading = 'Sales In a year';
    protected static ?int $sort = -8;
    protected int | string | array $columnSpan = 'full';
    protected static bool $isLazy = false;
    protected static string $view = 'filament.widgets.switch-panel';
}
