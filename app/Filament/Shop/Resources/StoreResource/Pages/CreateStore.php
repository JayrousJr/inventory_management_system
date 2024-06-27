<?php

namespace App\Filament\Shop\Resources\StoreResource\Pages;

use App\Models\Debt;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Filament\Resources\StoreResource;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;

class CreateStore extends CreateRecord
{

    use CreateRecord\Concerns\HasWizard;

    protected function getSteps(): array
    {
        return [

            Step::make('Basic Data')
                ->columns(3)
                ->icon('heroicon-o-shopping-bag')
                ->schema([
                    Hidden::make('user_id')
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->default(Auth::user()->id),
                    Hidden::make('shop_id')
                        ->default(Auth::user()->shop_id)
                        ->required(fn (string $operation): bool => $operation === 'create'),
                    TextInput::make('supplier')
                        ->label('Product Supplier Name')
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->readOnly(fn (string $operation): bool => $operation === 'edit')
                        ->maxLength(255),
                    TextInput::make('product_name')
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->readOnly(fn (string $operation): bool => $operation === 'edit')
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $name = $get('product_name');
                            $category = $get('category_id');

                            $set('product_name_category', $name . ' - ' . $category);
                        })
                        ->maxLength(255),
                    Select::make('category_id')
                        ->disabled(fn (string $operation): bool => $operation === 'edit')
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->label('Product Category')
                        ->options(Category::all()->pluck('category_name', 'category_name'))
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $name = $get('product_name');
                            $category = $get('category_id');
                            $set('product_name_category', $name . ' - ' . $category);
                        })
                        ->searchable(),
                    TextInput::make('product_name_category')
                        ->readOnly()
                        ->columnSpanFull()
                        // ->helperText('This is unique generated name')
                        ->unique(Product::class, 'product_name_category', fn ($record) => $record)
                        ->label('Product Generated Name'),
                ]),

            Step::make('Costs')
                ->columns(2)->icon('heroicon-m-currency-dollar')
                ->schema([
                    TextInput::make('quantity_in_store')
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->numeric()
                        ->columnSpanFull()
                        ->helperText('Product Quantity in the store')
                        ->label('Quantity')
                        ->live()
                        ->afterStateUpdated(function (callable $get, callable $set) {
                            $quantity = $get('quantity_in_store');
                            $buying_price = $get('buying_price');
                            $selling_price = $get('selling_price');
                            $amount_exp = floatval($quantity) * floatval($selling_price);

                            $total = floatval($quantity) * floatval($buying_price);
                            $set('total_cost', $total);
                            $set('amount_exp', $amount_exp);
                        })
                        ->prefix('UNIT(s)')
                        ->default(0),

                    TextInput::make('buying_price')
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->prefix('TSH')
                        ->helperText('Buying price for a unit product')
                        ->live()
                        ->afterStateUpdated(function (callable $get, callable $set) {
                            $quantity = $get('quantity_in_store');
                            $buying_price = $get('buying_price');

                            $total = floatval($quantity) * floatval($buying_price);
                            // $amount_exp = floatval($quantity) * floatval($selling_price);
                            $set('total_cost', $total);
                            // $set('amount_exp', $amount_exp);
                        })
                        ->numeric(),
                    TextInput::make('selling_price')
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->prefix('TSH')
                        ->helperText('Selling price for a unit product')
                        ->live()
                        ->afterStateUpdated(function (callable $get, callable $set) {
                            $quantity = $get('quantity_in_store');
                            $selling_price = $get('selling_price');

                            $amount_exp = floatval($quantity) * floatval($selling_price);
                            $set('amount_exp', $amount_exp);
                        })
                        ->numeric(),
                ]),
            Step::make('Payments')
                ->columns(2)->icon('heroicon-m-credit-card')
                ->schema([
                    TextInput::make('total_cost')
                        ->prefix('TSH')
                        ->readOnly()
                        ->helperText('Total Products Cost in the store')
                        ->label('Cost Price')
                        ->numeric(),
                    TextInput::make('amount_exp')
                        ->numeric()
                        ->readOnly()
                        ->helperText('Total Income after Selling all available Quantity in the store')
                        ->label('Sales Revenue')
                        ->prefix('TSH'),
                    TextInput::make('paid_amount')
                        ->columnSpanFull()
                        ->numeric()
                        ->live()
                        ->lte('total_cost')
                        ->readOnly(fn (string $operation): bool => $operation === 'edit')
                        ->required()
                        ->afterStateUpdated(function (callable $get, callable $set) {
                            $total_cost = $get('total_cost');
                            $paid_amount = $get('paid_amount');
                            $total = floatval($total_cost) - floatval($paid_amount);
                            $set('unpaid_amount', $total);
                        })
                        ->prefix('TSH'),
                    TextInput::make('unpaid_amount')
                        ->numeric()
                        ->columnSpanFull()
                        ->readOnly()
                        ->label('Debt')
                        ->prefix('TSH'),
                    Hidden::make('source')
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->default('store'),
                    Hidden::make('debt_type')
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->default('Debtor'),
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
                        ->label('Transaction Confirmed by')
                        ->default(Auth::user()->name),
                ]),
        ];
    }

    // protected function getCreateFormAction(): Action
    // {
    //     return Action::make('create')
    //         ->icon('heroicon-o-building-storefront')
    //         ->label('Save to Store')
    //         ->action(fn (CreateStore $livewire) => $livewire->create())
    //         ->requiresConfirmation()
    //         ->modalHeading('Confirm the saving to Store')
    //         ->modalDescription('Are you sure you have entered the correct details? Once Confirmed, you can not undo this Process!')
    //         ->modalSubmitActionLabel('Confirm')
    //         ->keyBindings(['mod+s']);
    // }

    protected static string $resource = StoreResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Bidhaa ime ongezwa stoo')
            ->icon('heroicon-o-plus')
            ->iconColor('success')
            ->body('Umefanikiwa kuongeza bidhaa stoo');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);
        //create a product
        $product = new Product();
        $product->shop_id = $data['shop_id'];
        $product->source_id = $record->id;
        $product->buying_price = $data['buying_price'];
        $product->selling_price = $data['selling_price'];
        $product->quantity = 0;
        $product->source_name = $data['source'];
        $product->product_name = $data['product_name'];
        $product->product_name_category = $data['product_name_category'];
        $product->category = $data['category_id'];
        $product->user_id = Auth::user()->id;
        $product->deleted_at = now();
        $product->save();

        // Pre Creating the debt table corresponding to the Store
        if ($data['unpaid_amount'] > 0) {
            $debt = new Debt();
            $debt->source_id = $record->id;
            $debt->shop_id = $data['shop_id'];
            $debt->s_or_c_name = $data['supplier'];
            $debt->total_amount = $data['total_cost'];
            $debt->paid_amount = $data['paid_amount'];
            $debt->remaining_amount = $data['unpaid_amount'];
            $debt->source_name = $data['source'];
            $debt->debt_type = $data['debt_type'];
            $debt->re_paid = 0;
            $debt->save();
        } else {
            $debt = new Debt();
            $debt->source_id = $record->id;
            $debt->shop_id = $data['shop_id'];
            $debt->s_or_c_name = $data['supplier'];
            $debt->total_amount = 0;
            $debt->paid_amount = 0;
            $debt->remaining_amount = 0;
            $debt->source_name = $data['source'];
            $debt->debt_type = $data['debt_type'];
            $debt->re_paid = 0;
            $debt->deleted_at = now();
            $debt->save();
        }
        return $record;
    }

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $data['quantity_in_store'] -= $data['quantity_to_shop'];

    //     return $data;
    // }
}
