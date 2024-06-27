<?php

namespace App\Filament\Resources\DebtResource\Pages;

use App\Models\Sale;
use App\Models\User;
use App\Models\Store;
use Filament\Actions;
use App\Models\Expense;
use Filament\Forms\Set;
use App\Models\Purchase;
// use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\KeyValue;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\DebtResource;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Wizard\Step;
use Filament\Notifications\Actions\Action;
use Filament\Infolists\Components\TextEntry;

class EditDebt extends EditRecord
{

    use EditRecord\Concerns\HasWizard;

    protected function getSteps(): array
    {
        return [
            Step::make('Basic Info')
                ->columns(3)
                ->description('Customer and Debts Information as recorded')
                ->icon('heroicon-m-users')
                ->schema([
                    TextInput::make('s_or_c_name')
                        ->required()
                        ->label('Supplier/ Customer name')
                        ->readOnly(),
                    TextInput::make('source_name')
                        ->required()
                        ->label('Debt Origin')
                        ->readOnly(),
                    TextInput::make('total_amount')
                        ->required()
                        ->prefix('TSH')
                        ->readOnly()
                        ->numeric(),
                    TextInput::make('paid_amount')
                        ->required()
                        ->readOnly()
                        ->prefix('TSH')
                        ->numeric(),
                    TextInput::make('re_paid')
                        ->label('Paying amount')
                        ->live()
                        ->afterStateUpdated(function (callable $get, callable $set) {
                            $re_paid = $get('re_paid');
                            $total_amount = $get('total_amount');
                            $paid_amount = $get('paid_amount');
                            $existing_payment = floatval($re_paid) + floatval($paid_amount);
                            $total = floatval($total_amount) - floatval($existing_payment);
                            $set('remaining_amount', $total);
                            $set('re_remaining_amount', $total);
                        })
                        ->prefix('TSH')
                        ->numeric(),
                    TextInput::make('remaining_amount')
                        ->readOnly()
                        ->prefix('TSH')
                        ->required()
                        ->numeric(),
                    Hidden::make('re_remaining_amount'),
                ]),
            Step::make('Confirmation')
                ->description('Payment confirmation')
                ->columns(2)
                ->icon('heroicon-m-check-badge')
                ->schema([
                    Toggle::make('confirm')
                        ->label('Confirm Debt Paymet')
                        ->onIcon('heroicon-m-bolt')
                        ->offIcon('heroicon-m-user')
                        ->onColor('success')
                        ->offColor('danger')
                        ->required()
                        ->accepted()
                        ->inline(true),
                ]),
        ];
    }
    // protected function getCreateFormAction(): Action
    // {
    //     return Action::make('create')
    //         ->icon('heroicon-o-currency-dollar')
    //         ->label('Edit debt')
    //         ->action(fn (EditDebt $livewire) => $livewire->create())
    //         ->requiresConfirmation()
    //         ->modalHeading('Confirm the Editing')
    //         ->modalDescription('Are you sure you have entered the correct details? Once Confirmed, you can not undo this Process!')
    //         ->modalSubmitActionLabel('Confirm')
    //         ->keyBindings(['mod+s']);
    // }

    protected static string $resource = DebtResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
    protected function getSavedNotification(): ?Notification
    {
        return

            // $recipient = User::where('id', [1, 2])->get();
            // $recipient = User::where('role', ['System Administrator', 'Manager']);

            // Notification::make()
            //     ->title('Saved successfully')
            //     ->sendToDatabase($recipient)

            Notification::make()
            ->success()
            ->title('Debt Paid')
            // ->icon('heroicon-o-pencil-alt')
            ->iconColor('success')
            ->body('Debt Payment have been done done successiful')
            // ->actions([
            //     Action::make('view')
            //         ->button()
            //         ->color('seondary'),
            // ->url(route('filament.pages.dashboard'), shouldOpenInNewTab: true),
            // Action::make('undo')
            //     ->color('warning')
            //     ->button(),
            // ->emit('UndoEditUser', [$post->id]),

            // ])
            ->sendToDatabase(auth()->user())
            ->send();
    }
    public function toDatabase(User $notifiable): array
    {
        return Notification::make()
            ->title('Saved successfully')
            ->getDatabaseMessage();
    }

    // protected function sendnot()
    // {
    //     $recipient = auth()->user();

    //     $recipient->notify(
    //         Notification::make()
    //             ->title('Saved successfully')
    //             ->toDatabase(),
    //     );
    // }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);
        $id = $record->source_id;
        $source_name = $record->source_name;

        if ($source_name === 'purchase') {
            $purchase = Purchase::find($id);
            if ($purchase) {
                if ($purchase->source === $source_name) {
                    $purchase->total_cost = $data['total_amount'];
                    $purchase->paid_amount = $data['re_remaining_amount'] + $data['re_paid'];
                    $purchase->unpaid_amount  = $data['remaining_amount'];
                }
                $purchase->save();
            } else
                exit(0);
        } else if ($source_name === 'store') {
            $store = Store::find($id);
            if ($store) {
                if ($store->source === $source_name) {
                    $store->total_cost = $data['total_amount'];
                    $store->paid_amount = $data['re_remaining_amount'] + $data['re_paid'];
                    $store->unpaid_amount  = $data['remaining_amount'];
                }
                $store->save();
            } else
                exit(0);
        } else if ($source_name === 'sale') {
            $sale = Sale::find($id);
            if ($sale) {
                if ($sale->source === $source_name) {
                    $sale->total_price = $data['total_amount'];
                    $sale->paid_amount = $data['re_remaining_amount'] + $data['re_paid'];
                    $sale->unpaid_amount  = $data['remaining_amount'];
                }
                $sale->save();
            } else
                exit(0);
        } else if ($source_name === 'expenses') {
            $expense = Expense::find($id);
            if ($expense) {
                if ($expense->source === $source_name) {
                    $expense->total_amount = $data['total_amount'];
                    $expense->paid_amount = $data['paid_amount'];
                    $expense->unpaid_amount  = $data['remaining_amount'];
                    $expense->updated_by  = Auth::user()->name;
                }
                $expense->save();
            }
        } else
            exit(0);

        return $record;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // if ($data['remaining_amount'] === 0) {
        //     $data['deleted_at'] = now();
        // }
        $data['edited_by'] = Auth::user()->name;
        $data['paid_amount'] += $data['re_paid'];
        return $data;
    }
}
