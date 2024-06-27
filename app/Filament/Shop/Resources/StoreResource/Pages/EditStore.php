<?php

namespace App\Filament\Shop\Resources\StoreResource\Pages;

use App\Models\Debt;
use App\Models\Store;
use Filament\Actions;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use function PHPSTORM_META\exitPoint;
use Illuminate\Database\Eloquent\Model;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Shop\Resources\StoreResource;

class EditStore extends EditRecord
{
    protected static string $resource = StoreResource::class;

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
        $source_name = $record->source;

        if ($source_name === 'store') {

            if ($data['quantity_to_shop'] > 0) {
                $product = Product::withTrashed()->where('source_id', $id)->where('source_name', $source_name)->first();
                if ($product) {

                    $product->buying_price = $data['buying_price'];
                    $product->selling_price = $data['selling_price'];

                    $product->quantity += $data['quantity_to_shop'];
                    $product->deleted_at = NULL;
                    $product->edited_by  = Auth::user()->name;

                    $product->save();
                } else
                    exit(0);
            }


            if ($data['re_unpaid_amount'] > 0) {
                $debt = Debt::withTrashed()->where('source_id', $id)->where('source_name', $source_name)->first();
                if ($debt) {
                    $debt->total_amount += $data['add_to_store'] * $data['buying_price'];
                    $debt->paid_amount += $data['re_paid_amount'];
                    $debt->remaining_amount += $data['re_unpaid_amount'];
                    $debt->deleted_at = NULL;
                    $debt->save();
                } else
                    exit(0);
            }
        }
        return $record;
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['quantity_in_store'] = $data['quantity_rem'];
        $data['amount_exp'] += $data['re_amount_exp'];
        $data['total_cost'] += $data['re_total_cost'];
        $data['paid_amount'] += $data['re_paid_amount'];
        $data['unpaid_amount'] += $data['re_unpaid_amount'];

        return $data;
    }
}
