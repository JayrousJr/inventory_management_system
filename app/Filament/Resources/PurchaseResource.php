<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\ActionSize;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PurchaseResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PurchaseResource\RelationManagers;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static ?string $navigationGroup = 'Products Management';
    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?int $navigationSort = 3;

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
                                    ->live()
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
                Fieldset::make('Quantity and Price')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('buying_price')
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->prefix('TSH')
                                    ->helperText('Buying price for a unit product')
                                    ->live()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        $quantity = $get('adjust_quantity');
                                        $buying_price = $get('buying_price');

                                        $total = floatval($quantity) * floatval($buying_price);
                                        $set('total_cost', $total);
                                    })
                                    ->numeric(),
                                Forms\Components\TextInput::make('selling_price')
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->prefix('TSH')
                                    ->helperText('Selling price for a unit product')
                                    ->live()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        $quantity = $get('adjust_quantity');
                                        $selling_price = $get('selling_price');

                                        $amount_exp = floatval($quantity) * floatval($selling_price);
                                        $set('amount_exp', $amount_exp);
                                    })
                                    ->numeric(),
                            ]),
                    ]),

                Fieldset::make('Quantity Adjustment')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('quantity_in_purchase')
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->readOnly(fn (string $operation): bool => $operation === 'edit')
                                    ->numeric()
                                    ->helperText('Product Quantity in the store')
                                    ->label('Quantity')
                                    ->live()
                                    ->prefix('UNIT(s)')
                                    ->default(0),
                                Forms\Components\TextInput::make('adjust_quantity')
                                    ->prefix('TSH')
                                    ->helperText('Enter Produst Quantity to add in / Adjust')
                                    ->label('Adjusting Quantity')
                                    ->live()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        $quantity = $get('quantity_in_purchase');
                                        $adjust_quantity = $get('adjust_quantity');
                                        $buying_price = $get('buying_price');
                                        $selling_price = $get('selling_price');
                                        $total_quantity = floatval($adjust_quantity + floatval($quantity));

                                        $total_cost = floatval($adjust_quantity) * floatval($buying_price);
                                        $amount_exp = floatval($adjust_quantity) * floatval($selling_price);


                                        $set('quantity_after_adjustment', $total_quantity);

                                        $set('total_cost', $total_cost);
                                        $set('amount_exp', $amount_exp);
                                    })
                                    ->numeric(),
                                Forms\Components\TextInput::make('quantity_after_adjustment')
                                    ->readOnly(fn (string $operation): bool => $operation === 'edit')
                                    ->numeric()
                                    ->readOnly()
                                    ->helperText('Product quantity after Adjustments to be made')
                                    ->label('Quantity after Adjustment')
                                    ->prefix('TSH'),
                            ])
                    ]),
                Fieldset::make('Costs')
                    ->schema([
                        Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('total_cost')
                                    ->prefix('TSH')
                                    ->readOnly()
                                    ->helperText('Total cost of the adjusted products in purchase')
                                    ->label('Cost Price')
                                    ->numeric(),
                                Forms\Components\TextInput::make('amount_exp')
                                    ->numeric()
                                    ->readOnly()
                                    ->helperText('Total Income after Selling all available Quantity in the store')
                                    ->label('Sales Revenue')
                                    ->prefix('TSH'),
                            ])
                    ]),
                Fieldset::make('Payments and Debts')
                    ->schema([
                        Grid::make()
                            ->schema([

                                Forms\Components\Hidden::make('paid_amount'),
                                Forms\Components\Hidden::make('unpaid_amount'),
                                Forms\Components\TextInput::make('re_paid_amount')
                                    ->numeric()
                                    ->label('Paid Amount')
                                    ->live()
                                    ->lte('total_cost')
                                    ->required()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        $total_cost = $get('total_cost');
                                        $re_paid_amount = $get('re_paid_amount');
                                        $total = floatval($total_cost) - (floatval($re_paid_amount));
                                        $set('re_unpaid_amount', $total);
                                    })
                                    ->prefix('TSH'),
                                Forms\Components\TextInput::make('re_unpaid_amount')
                                    ->numeric()
                                    ->readOnly()
                                    ->label('Debt')
                                    ->prefix('TSH'),
                                Forms\Components\Hidden::make('source')
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->default('store'),
                                Forms\Components\Hidden::make('debt_type')
                                    ->required(fn (string $operation): bool => $operation === 'create')
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
                    ->description(fn (Purchase $record): string => 'Supplier: ' . $record->supplier, position: 'above')
                    ->description(fn (Purchase $record): string => 'Category: ' . $record->category_id, position: 'below')
                    ->icon('heroicon-s-archive-box-arrow-down')
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity_in_purchase')
                    ->numeric()
                    ->description(fn (Purchase $record): string => 'Selling Price: TSH ' . number_format((float)$record->selling_price, 0, '.', ','), position: 'above')
                    ->description(fn (Purchase $record): string => 'Buying Price: TSH ' . number_format((float)$record->buying_price, 0, '.', ','))
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
                    ->money('Tsh')
                    ->description(fn (Purchase $record): string => 'Paid: Tsh ' . number_format((float)$record->paid_amount, 0, '.', ','), position: 'below')
                    ->description('Value of Product(s)')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('unpaid_amount')
                    ->numeric()
                    ->label('Unpaid Amount')
                    ->money('Tsh')
                    ->description(fn (Purchase $record): string => $record->unpaid_amount == 0 ? 'No Debt' : 'Under Debt')
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
                    ->description(fn (Purchase $record): string => date('M D Y H:i', strtotime($record->created_at)))
                    ->color('primary'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y D M')
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
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'view' => Pages\ViewPurchase::route('/{record}'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
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
