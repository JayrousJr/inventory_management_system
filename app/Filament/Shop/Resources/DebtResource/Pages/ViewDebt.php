<?php

namespace App\Filament\Shop\Resources\DebtResource\Pages;

use App\Filament\Shop\Resources\DebtResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDebt extends ViewRecord
{
    protected static string $resource = DebtResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
