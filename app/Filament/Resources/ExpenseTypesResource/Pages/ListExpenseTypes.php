<?php

namespace App\Filament\Resources\ExpenseTypesResource\Pages;

use App\Filament\Resources\ExpenseTypesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExpenseTypes extends ListRecords
{
    protected static string $resource = ExpenseTypesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
