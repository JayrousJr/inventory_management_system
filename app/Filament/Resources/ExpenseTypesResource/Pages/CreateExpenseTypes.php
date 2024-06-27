<?php

namespace App\Filament\Resources\ExpenseTypesResource\Pages;

use App\Filament\Resources\ExpenseTypesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExpenseTypes extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = ExpenseTypesResource::class;
}
