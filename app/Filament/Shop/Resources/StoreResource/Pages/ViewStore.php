<?php

namespace App\Filament\Shop\Resources\StoreResource\Pages;

use App\Filament\Shop\Resources\StoreResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStore extends ViewRecord
{
    protected static string $resource = StoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
