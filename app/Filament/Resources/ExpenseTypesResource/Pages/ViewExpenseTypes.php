<?php

namespace App\Filament\Resources\ExpenseTypesResource\Pages;

use App\Filament\Resources\ExpenseTypesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewExpenseTypes extends ViewRecord
{
    protected static string $resource = ExpenseTypesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
