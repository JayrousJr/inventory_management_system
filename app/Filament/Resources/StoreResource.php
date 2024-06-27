<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Store;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\ActionSize;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StoreResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StoreResource\RelationManagers;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $navigationGroup = 'Products Management';
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'product_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Fieldset::make('Product Informations')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                Forms\Components\Hidden::make('user_id')
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->default(Auth::user()->id),
                                Forms\Components\Hidden::make('shop_id')
                                    ->default(Auth::user()->shop_id)
                                    ->required(fn (string $operation): bool => $operation === 'create'),
                                Forms\Components\TextInput::make('supplier')
                                    ->label('Product Supplier Name')
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->readOnly(fn (string $operation): bool => $operation === 'edit')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('product_name')
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->readOnly(fn (string $operation): bool => $operation === 'edit')
                                    ->live(debounce: '30s')
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $name = $get('product_name');
                                        $category = $get('category_id');

                                        $set('product_name_category', $name . ' - ' . $category);
                                    })
                                    ->maxLength(255),
                                Forms\Components\Select::make('category_id')
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
                                Forms\Components\TextInput::make('product_name_category')
                                    ->unique(fn (string $operation): bool => $operation === 'create')
                                    ->readOnly()
                                    // ->helperText('This is unique generated name')
                                    ->label('Product Generated Name'),

                            ]),
                    ]),
                Fieldset::make('Quantity Adjustments To shop')
                    ->schema([
                        Grid::make(2)
                            ->schema([

                                Forms\Components\TextInput::make('buying_price')
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->prefix('TSH')
                                    ->helperText('Buying price for a unit product')
                                    ->live()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        $quantity = $get('add_to_store');
                                        $buying_price = $get('buying_price');

                                        $total = floatval($quantity) * floatval($buying_price);

                                        $set('re_total_cost', $total);
                                    })
                                    ->numeric(),
                                Forms\Components\TextInput::make('selling_price')
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->prefix('TSH')
                                    ->helperText('Selling price for a unit product')
                                    ->live()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        $quantity = $get('add_to_store');
                                        $selling_price = $get('selling_price');

                                        $amount_exp = floatval($quantity) * floatval($selling_price);
                                        $set('re_amount_exp', $amount_exp);
                                    })
                                    ->numeric(),
                            ]),
                    ]),




                Fieldset::make('Store Quantity Adjustments')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('quantity_in_store')
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->numeric()
                                    ->readOnly()
                                    ->helperText('Product Count in store')
                                    ->label('Available Quantity')
                                    ->prefix('UNIT(s)')
                                    ->default(0),
                                Forms\Components\TextInput::make('add_to_store')
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    // ->visible(fn (string $operation): bool => $operation === 'edit')
                                    ->numeric()
                                    ->default(0)
                                    ->label('Adjust Quantity to Store')
                                    ->helperText('Adjust to transfer products to shop')
                                    ->prefix('UNIT(s)')
                                    ->live()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        $buying_price = $get('buying_price');
                                        $selling_price = $get('selling_price');

                                        $quantity = floatval($get('quantity_in_store'));
                                        $quantity_adjust = floatval($get('add_to_store'));
                                        $quantity_to_shop = floatval($get('quantity_to_shop'));
                                        $total = floatval($quantity_adjust) * floatval($buying_price);
                                        $amount_exp = floatval($quantity_adjust) * floatval($selling_price);
                                        $total_store_quantity = $quantity + $quantity_adjust;
                                        $quantity_rem = $quantity + $quantity_adjust - $quantity_to_shop;

                                        $set('re_total_cost', $total);
                                        $set('re_amount_exp', $amount_exp);
                                        $set('total_store_quantity', $total_store_quantity);
                                        $set('quantity_rem', $quantity_rem);
                                    })
                                    ->default(0),
                                Forms\Components\TextInput::make('total_store_quantity')
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->numeric()
                                    ->readOnly()
                                    ->helperText('Total Store quantity Count')
                                    ->label('Total Quantity after Adjustiments')
                                    ->live()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        $total_store_quantity = $get('total_store_quantity');
                                        $set('quantity_rem', floatval($total_store_quantity));
                                    })
                                    ->prefix('UNIT(s)')
                                    ->default(0),
                            ]),
                    ]),

                Fieldset::make('Quantity Adjustments To shop')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('quantity_to_shop')
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    // ->visible(fn (string $operation): bool => $operation === 'edit')
                                    ->numeric()
                                    ->gt(0)
                                    ->lte('quantity_in_store')
                                    ->label('Quantity sending to shop')
                                    ->helperText('Adjust to transfer products to shop')
                                    ->prefix('UNIT(s)')
                                    ->live()
                                    ->default(0)
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        $quantity = floatval($get('quantity_in_store'));
                                        $quantity_adjust = floatval($get('add_to_store'));
                                        $quantity_to_shop = floatval($get('quantity_to_shop'));

                                        $total_store_quantity = $quantity + $quantity_adjust;
                                        $quantity_rem = $quantity + $quantity_adjust - $quantity_to_shop;
                                        $set('total_store_quantity', $total_store_quantity);
                                        $set('quantity_rem', $quantity_rem);
                                    })
                                    ->default(0),
                                Forms\Components\TextInput::make('quantity_rem')
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->numeric()
                                    ->readOnly()
                                    ->helperText('Remaining product Count after sending some to shop')
                                    ->label('Remaining Quantity After Adjustments to shop')
                                    ->prefix('UNIT(s)')
                                    ->default(0),

                            ]),
                    ]),

                Fieldset::make('Previoud Payments and Debts')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('total_cost')
                                    ->prefix('TSH')
                                    ->label('Prevous Cost Price')
                                    ->readOnly()
                                    ->numeric(),
                                Forms\Components\TextInput::make('amount_exp')
                                    ->numeric()
                                    ->label('Previous Sales Revenue')
                                    ->prefix('TSH')
                                    ->readOnly(),
                                Forms\Components\TextInput::make('paid_amount')
                                    ->readOnly()
                                    ->numeric()
                                    ->label('Previous Paid Amount')
                                    ->prefix('TSH'),
                                Forms\Components\TextInput::make('unpaid_amount')
                                    ->numeric()
                                    ->readOnly()
                                    ->label('Previous Debt')
                                    ->prefix('TSH'),
                            ])
                    ]),
                Fieldset::make('Current Payments and Debts')
                    ->schema([
                        Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('re_total_cost')
                                    ->prefix('TSH')
                                    ->label('Cost Price')
                                    ->readOnly()
                                    ->numeric()
                                    ->hidden(fn (string $operation): bool => $operation === 'view')
                                    ->helperText('Total Products Cost in the store'),
                                Forms\Components\TextInput::make('re_amount_exp')
                                    ->numeric()
                                    ->label('Sales Revenue')
                                    ->prefix('TSH')
                                    ->readOnly()
                                    ->hidden(fn (string $operation): bool => $operation === 'view')
                                    ->helperText('Total Income after Selling all available Quantity in the store'),
                                Forms\Components\TextInput::make('re_paid_amount')
                                    ->numeric()
                                    ->label('Paid Amount')
                                    ->live()
                                    ->prefix('TSH')
                                    ->lte('re_total_cost')
                                    ->hidden(fn (string $operation): bool => $operation === 'view')
                                    ->required()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        $total_cost = $get('re_total_cost');
                                        $paid_amount = $get('re_paid_amount');
                                        $total = floatval($total_cost) - floatval($paid_amount);
                                        $set('re_unpaid_amount', $total);
                                    }),
                                Forms\Components\TextInput::make('re_unpaid_amount')
                                    ->numeric()
                                    ->readOnly()
                                    ->label('Debt')
                                    ->prefix('TSH')
                                    ->hidden(fn (string $operation): bool => $operation === 'view'),

                                Forms\Components\Hidden::make('source')
                                    ->default('store'),
                                Forms\Components\Hidden::make('debt_type')
                                    ->default('Debtor'),
                            ])
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('product_name')
                    ->label('Product')
                    ->description(fn (Store $record): string => 'Supplier: ' . $record->supplier, position: 'above')
                    ->description(fn (Store $record): string => 'Category: ' . $record->category_id, position: 'below')
                    ->icon('heroicon-s-archive-box-arrow-down')
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity_in_store')
                    ->numeric()
                    ->description(fn (Store $record): string => 'Selling Price: TSH ' . number_format((float)$record->selling_price, 0, '.', ','), position: 'above')
                    ->description(fn (Store $record): string => 'Buying Price: TSH ' . number_format((float)$record->buying_price, 0, '.', ','))
                    ->sortable()
                    ->icon('heroicon-s-calculator')
                    ->color(function (string $state) {
                        if ($state == '0')
                            return 'danger';
                        else {
                            return 'primary';
                        }
                    }),
                Tables\Columns\TextColumn::make('total_cost')
                    ->money('Tsh')
                    ->sortable()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('paid_amount')
                    ->money('Tsh')
                    ->sortable()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('unpaid_amount')
                    ->numeric()
                    ->label('Unpaid Amount')
                    ->money('Tsh')
                    ->description(fn (Store $record): string => $record->unpaid_amount == 0 ? 'No Debt' : 'Under Debt')
                    ->sortable()
                    ->color(function (string $state) {
                        if ($state != '0')
                            return 'danger';
                        else {
                            return 'primary';
                        }
                    }),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Created by')
                    ->icon('heroicon-s-user')
                    ->description(fn (Store $record): string => date('M D Y H:i', strtotime($record->created_at)))
                    ->color('primary'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->size(ActionSize::Small)
                    ->icon('heroicon-m-ellipsis-horizontal')
                    ->tooltip('Actions')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')

            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'view' => Pages\ViewStore::route('/{record}'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
