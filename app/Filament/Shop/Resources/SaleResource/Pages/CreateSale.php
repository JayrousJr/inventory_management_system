<?php

namespace App\Filament\Shop\Resources\SaleResource\Pages;

use Closure;
use App\Models\Debt;
use App\Models\User;
use Filament\Actions;
use App\Models\Product;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Dompdf\FrameDecorator\Text;
use CreateRecord\Concerns\HasWizard;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\SaleResource;
use App\Models\Purchase;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Forms;

class CreateSale extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = SaleResource::class;

    protected function getSteps(): array
    {
        return [

            Step::make('Customer')
                ->columns(2)->icon('heroicon-m-users')
                ->schema([
                    Select::make('user_id')
                        ->options(User::where('role', 'Customer')->where('shop_id', Auth::user()->shop_id)->pluck('name', 'id'))
                        ->searchable()
                        ->label('Customer id')
                        ->live()
                        ->preload()
                        ->afterStateUpdated(function ($state, Set $set) {
                            $user = User::find($state);
                            $set('customer_name', $user->name);
                        })
                        ->required(),
                    TextInput::make('customer_name')
                        ->readOnly()
                        ->required(),

                    // ->unique(Category::class, 'slug', fn ($record) => $record),
                ]),
            Step::make('Product')
                ->columns(2)
                ->icon('heroicon-m-shopping-cart')
                ->schema([
                    Select::make('product_id')
                        ->options(Product::where('shop_id', Auth::user()->shop_id)->where('quantity', '>', '0')->pluck('product_name_category', 'id'))
                        ->searchable()
                        ->live()
                        ->preload()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $product = Product::find($state);
                            $pro_buying_price = $product->buying_price;
                            $pro_selling_price = $product->selling_price;
                            $product_name = $product->product_name;
                            $product_name_category = $product->product_name_category;
                            $category = $product->category;
                            $quantity = $product->quantity;

                            $set('pro_buying_price', $pro_buying_price);
                            $set('pro_selling_price', $pro_selling_price);
                            $set('category', $category);
                            $set('product_name', $product_name);
                            $set('product_name_category', $product_name_category);
                            $set('quantity', $quantity);
                            $set('shop_id', $product->shop_id);
                            $set('product_id', $product->id);
                            $set('source_id', $product->source_id);
                        })
                        ->required(),
                    Hidden::make('debt_type')->default('Creditor'),
                    Hidden::make('source_id'),
                    Hidden::make('shop_id'),
                    Hidden::make('product_id'),
                    TextInput::make('product_name')
                        ->readOnly()
                        ->maxLength(255),
                    TextInput::make('product_name_category')
                        ->readOnly()
                        ->maxLength(255),
                    TextInput::make('category')
                        ->readOnly()
                        ->maxLength(255),

                ]),
            Step::make('Prices')
                ->icon('heroicon-m-currency-dollar')
                ->columns(2)
                ->schema([
                    TextInput::make('pro_buying_price')
                        ->required()
                        ->prefix('Tsh')
                        ->readOnly()
                        ->numeric(),
                    TextInput::make('pro_selling_price')
                        ->required()
                        ->prefix('Tsh')
                        ->readOnly()
                        ->numeric(),
                    TextInput::make('quantity')
                        ->label('Available Quantity')
                        ->readOnly()
                        ->maxLength(255),
                    TextInput::make('product_quantity_sold')
                        ->required()
                        ->lte('quantity')
                        ->rules([
                            function () {
                                return function (string $attribute, $value, Closure $fail) {
                                    if ($value <= 0) {
                                        $fail('The :attribute must be Greater than 0 and Less or Equal to Available Quantity');
                                    }
                                };
                            },
                        ])
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {

                            $pro_selling_price = floatval($get('pro_selling_price'));
                            $pro_buying_price = floatval($get('pro_buying_price'));
                            $quantity = floatval($get('product_quantity_sold'));

                            $unit_profit = $pro_selling_price - $pro_buying_price;

                            $profit = $unit_profit * $quantity;

                            $total_price = $pro_selling_price * $quantity;

                            $set('profit', $profit);
                            $set('total_price', $total_price);
                        })
                        ->numeric(),
                ]),
            Step::make('Payments')
                ->icon('heroicon-m-credit-card')
                ->columns(2)
                ->schema([
                    TextInput::make('total_price')
                        ->prefix('Tsh')
                        ->readOnly()
                        ->numeric(),
                    TextInput::make('profit')
                        ->readOnly()
                        ->prefix('Tsh')
                        ->label('Profit to be made for this sale')
                        ->numeric(),
                    TextInput::make('paid_amount')
                        ->prefix('Tsh')
                        ->required()
                        ->lte('total_price')
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {

                            $total_price = floatval($get('total_price'));
                            $paid_amount = floatval($get('paid_amount'));

                            $unpaid_amount = $total_price - $paid_amount;

                            $set('unpaid_amount', $unpaid_amount);
                        })
                        ->numeric(),
                    TextInput::make('unpaid_amount')
                        ->label('Debt')
                        ->prefix('Tsh')
                        ->readOnly()
                        ->numeric(),
                    Hidden::make('saler_name')
                        ->default(Auth::user()->name),
                ]),
            Step::make('Confirmation')
                ->icon('heroicon-m-check-badge')
                ->columns(1)
                ->schema([
                    Toggle::make('confirm')
                        ->label('Confirm selling')
                        ->onIcon('heroicon-m-bolt')
                        ->offIcon('heroicon-s-exclamation-triangle')
                        ->onColor('success')
                        ->offColor('danger')
                        ->required()
                        ->accepted()
                        ->inline(true),
                    TextInput::make('saler_name')
                        ->readOnly()
                        ->label('Sale Confirmed by')
                        ->default(Auth::user()->name),
                ]),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Bidhaa imeuzwa')
            ->icon('heroicon-o-plus')
            ->iconColor('success')
            ->body('Umefanikiwa Kuuza bidhaa kwa mteja');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);
        $id = $record->product_id;
        $source_id = $record->source_id;

        if ($data['unpaid_amount'] > 0) {
            // 
            $debt = new Debt();
            $debt->source_id = $record->id;
            $debt->shop_id = $data['shop_id'];
            $debt->s_or_c_name = $data['customer_name'];
            $debt->total_amount = $data['total_price'];
            $debt->paid_amount = $data['paid_amount'];
            $debt->remaining_amount = $data['unpaid_amount'];
            $debt->source_name = 'sale';
            $debt->debt_type = 'Creditor';

            $debt->save();
        }

        // $record->update($data);
        $product = Product::find($id);
        $product->quantity -= $data['product_quantity_sold'];
        $product->save();

        $purchase = Purchase::where('id', $source_id)->first();
        if ($purchase) {
            $purchase->quantity_in_purchase -= $data['product_quantity_sold'];
            if ($purchase->quantity_in_purchase <= 0) {
                $purchase->quantity_in_purchase = 0;
                $purchase->total_cost = 0;
                $purchase->amount_exp = 0;
            } else {
                $purchase->quantity_in_purchase -= $data['product_quantity_sold'];
                $quantity = $purchase->quantity_in_purchase;
                $purchase->total_cost = $quantity * $purchase->buying_price;
                $purchase->amount_exp = $quantity * $purchase->selling_price;
            }
            $purchase->save();
        } else
            exit();
        return $record;
    }


    // protected function beforeCreate()
    // {
    //     Action::make('create')
    //         ->icon('heroicon-o-shopping-cart')
    //         ->label('Make sale')
    //         ->action(fn (CreateSale $livewire) => $livewire->create())
    //         ->requiresConfirmation()
    //         ->modalHeading('Confirm sale')
    //         ->modalDescription('Are you sure you have entered the correct details? Once Confirmed, you can not undo this sale!')
    //         ->modalSubmitActionLabel('Confirm')
    //         ->keyBindings(['mod+s']);
    // }
    // protected function getCreateFormAction(): Action
    // {
    //     return Action::make('create')
    //         ->icon('heroicon-o-shopping-cart')
    //         ->label('Make sale')
    //         ->action(fn (CreateSale $livewire) => $livewire->create())
    //         ->requiresConfirmation()
    //         ->modalHeading('Confirm sale')
    //         ->modalDescription('Are you sure you have entered the correct details? Once Confirmed, you can not undo this sale!')
    //         ->modalSubmitActionLabel('Confirm')
    //         ->keyBindings(['mod+s']);
    // }
}
