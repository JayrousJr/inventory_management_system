<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ExpenseResource;
use App\Filament\Resources\ExpenseResource\Widgets\ExpensesOverview;

class ListExpenses extends ListRecords
{
    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // ExpensesOverview::class,
        ];
    }
}
