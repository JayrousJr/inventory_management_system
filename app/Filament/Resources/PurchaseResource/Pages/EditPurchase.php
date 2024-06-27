<?php

namespace App\Filament\Resources\PurchaseResource\Pages;

use Filament\Actions;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PurchaseResource;
use App\Models\Debt;

class EditPurchase extends EditRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);
        $id = $record->id;
        $source = $record->source;
        // fetch Product to edit

        $product = Product::where('source_id', $id)->first();
        if ($product) {
            $product->quantity += $data['adjust_quantity'];
            $product->edited_by  = Auth::user()->name;
            $product->save();
        } else
            exit(0);

        // fetch debt to edit
        if ($data['unpaid_amount'] > 0) {
            $debt = Debt::withTrashed()->where('source_id', $id)->where('source_name', $source)->first();
            if ($debt) {
                $debt->total_amount += $data['adjust_quantity'] * $data['buying_price'];
                $debt->paid_amount += $data['re_paid_amount'];
                $debt->remaining_amount += $data['re_unpaid_amount'];
                $debt->deleted_at = NULL;
                $debt->save();
            } else
                exit(0);
        }
        return $record;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['quantity_in_purchase'] = $data['quantity_after_adjustment'];
        $data['paid_amount'] += $data['re_paid_amount'];
        $data['unpaid_amount'] += $data['re_unpaid_amount'];
        $data['total_cost'] = $data['quantity_in_purchase'] * $data['buying_price'];
        // $data['amount_exp'] = $data['quantity_in_store'] * $data['selling_price'];

        return $data;
    }
}