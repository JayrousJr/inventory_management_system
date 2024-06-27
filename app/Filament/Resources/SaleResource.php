<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use App\Models\Sale;
use App\Models\User;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Toggle;
use Filament\Support\Enums\ActionSize;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\SaleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Filament\Resources\SaleResource\Widgets\SaleOVerview;
use App\Filament\Resources\SaleResource\Widgets\SalesOverView;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static ?string $navigationGroup = 'Sales & Debts Management';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'customer_name';

    // public static function getEloquentQuery(): Builder
    // {
    //     $shopid = Auth::user()->shop_id;

    //     return Sale::query()->where('shop_id', $shopid)->get();
    // }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->options(User::where('role', 'Customer')->where('shop_id', Auth::user()->shop_id)->pluck('name', 'id'))
                    ->searchable()
                    ->live(debounce: '6s')
                    ->preload()
                    ->afterStateUpdated(function ($state, Set $set) {
                        $user = User::find($state);
                        $set('customer_name', $user->name);
                    })
                    ->required(),
                Forms\Components\Select::make('product_id')
                    ->options(Product::where('shop_id', Auth::user()->shop_id)->where('quantity', '>', '0')->pluck('product_name_category', 'id'))
                    ->searchable()
                    ->live(debounce: '6s')
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
                        $set('source_id', $product->source_id);
                        $set('product_id', $product->id);
                    })
                    ->required(),
                Forms\Components\Hidden::make('customer_name'),
                Forms\Components\Hidden::make('source_id'),
                Forms\Components\TextInput::make('pro_buying_price')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('pro_selling_price')
                    ->required()
                    ->numeric(),
                Forms\Components\Hidden::make('debt_type')->default('Creditor'),
                // Forms\Components\TextInput::make('shop_id'),
                // Forms\Components\TextInput::make('product_id'),
                Forms\Components\TextInput::make('product_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('product_name_category')
                    ->maxLength(255),
                Forms\Components\TextInput::make('category')
                    ->maxLength(255),
                // Forms\Components\TextInput::make('quantity')
                //     ->label('Available Quantity')
                //     ->maxLength(255),
                Forms\Components\TextInput::make('product_quantity_sold')
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
                    ->live(debounce: '5s')
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
                Forms\Components\TextInput::make('total_price')
                    ->required()
                    ->readOnly()
                    ->numeric(),
                Forms\Components\TextInput::make('paid_amount')
                    ->required()
                    ->lte('total_price')
                    ->live(debounce: '5s')
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {

                        $total_price = floatval($get('total_price'));
                        $paid_amount = floatval($get('paid_amount'));

                        $unpaid_amount = $total_price - $paid_amount;

                        $set('unpaid_amount', $unpaid_amount);
                    })
                    ->numeric(),
                Forms\Components\TextInput::make('unpaid_amount')
                    ->required()
                    ->readOnly()
                    ->numeric(),
                Forms\Components\TextInput::make('profit')
                    ->numeric(),
                Forms\Components\hidden::make('saler_name')
                    ->default(Auth::user()->name),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->icon('heroicon-m-user-circle')
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Product')
                    ->description(fn (Sale $record): string => 'Category: ' . $record->category, position: 'above')
                    ->sortable()
                    ->color(function (string $state) {
                        if ($state == '0')
                            return 'danger';
                        else {
                            return 'primary';
                        }
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('product_quantity_sold')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product_quantity_sold')
                    ->numeric()
                    ->label('Quantity sold')
                    ->description(fn (Sale $record): string => 'Price: Tsh ' . number_format((float)$record->total_price, 0, '.', ','), position: 'above')
                    ->description(fn (Sale $record): string => 'Profit: Tsh ' .  number_format((float)$record->profit, 0, '.', ','))
                    ->icon('heroicon-s-calculator')
                    ->color(function (string $state) {
                        if ($state == '0')
                            return 'danger';
                        else {
                            return 'primary';
                        }
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('unpaid_amount')
                    ->numeric()
                    ->label('Unpaid Amount')
                    ->money('Tsh')
                    ->description(fn (Sale $record): string => $record->unpaid_amount == 0 ? 'No Debt' : 'Under Debt')
                    ->color(function (string $state) {
                        if ($state != '0')
                            return 'danger';
                        else {
                            return 'primary';
                        }
                    }),
                Tables\Columns\TextColumn::make('saler_name')
                    ->icon('heroicon-s-user')
                    ->description(fn (Sale $record): string => date('M D Y H:i', strtotime($record->created_at)))
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
                Tables\Actions\Action::make('pdf')
                    ->label('Print')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Sale $record) => route('pdf', $record)),
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->size(ActionSize::Small)
                    ->icon('heroicon-m-ellipsis-horizontal')
                    ->tooltip('Actions')
            ])
            ->defaultSort('created_at', 'desc')

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
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'view' => Pages\ViewSale::route('/{record}'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
        return Sale::query()->where('shop_id', Auth::user()->shop_id);
    }
}
