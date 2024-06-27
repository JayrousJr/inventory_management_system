<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use App\Models\Debt;
use Filament\Actions;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ExpenseResource;

class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);
        $id = $record->product_id;
        if ($data['unpaid_amount'] > 0) {
            // 
            $debt = new Debt();
            $debt->source_id = $record->id;
            $debt->shop_id = $data['shop_id'];
            $debt->s_or_c_name = $data['expense_type'];
            $debt->total_amount = $data['total_amount'];
            $debt->paid_amount = $data['paid_amount'];
            $debt->remaining_amount = $data['unpaid_amount'];
            $debt->source_name = 'expenses';
            $debt->debt_type = 'Debtor';

            $debt->save();
        }

        // $record->update($data);
        // $product = Product::find($id);
        // $product->quantity -= $data['product_quantity_sold'];
        // $product->save();

        return $record;
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->icon('heroicon-o-shopping-cart')
            ->label('Create Expenses')
            ->action(fn (CreateExpense $livewire) => $livewire->create())
            ->requiresConfirmation()
            ->modalHeading('Confirm Expenses')
            ->modalDescription('Are you sure you have entered the correct details? Once Confirmed, you can not undo this sale!')
            ->modalSubmitActionLabel('Confirm')
            ->keyBindings(['mod+s']);
    }
}
