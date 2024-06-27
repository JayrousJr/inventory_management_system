<?php

namespace App\Filament\Shop\Resources;


use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Fieldset;
use Filament\Infolists\Components\Split;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    // protected static ?string $recordTitleAttribute = 'product_name';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static ?string $navigationGroup = 'Products Management';
    protected static ?string $navigationIcon = 'heroicon-m-archive-box';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Product Basic Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Hidden::make('shop_id'),
                                Forms\Components\TextInput::make('product_name')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('category')
                                    ->label('Product Category')
                                    ->maxLength(255),


                            ]),
                    ]),
                Fieldset::make('Product Price Details')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('quantity')
                                    ->required()
                                    ->label('Available Quantity')
                                    ->prefix('Unit(s)')
                                    ->default(0),
                                Forms\Components\TextInput::make('buying_price')
                                    ->required()
                                    ->prefix('Tsh'),
                                Forms\Components\TextInput::make('selling_price')
                                    ->required()
                                    ->prefix('Tsh'),
                            ]),
                    ]),
                Fieldset::make('Product Updating Details')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('edited_by')
                                    ->default('Not Edited')
                                    ->maxLength(255),
                            ]),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product_name')
                    ->description(fn (Product $record): string => 'Category: ' . $record->category, position: 'below')
                    ->icon('heroicon-s-archive-box-arrow-down')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('quantity')
                    ->description(fn (Product $record): string => 'Selling Price: TSH ' . number_format((float)$record->selling_price, 0, '.', ','), position: 'above')
                    ->description(fn (Product $record): string => 'Buying Price: TSH ' . number_format((float)$record->buying_price, 0, '.', ','))
                    ->icon('heroicon-s-calculator')
                    ->color(function (string $state) {
                        if ($state == '0')
                            return 'danger';
                        else {
                            return 'primary';
                        }
                    }),
                Tables\Columns\TextColumn::make('source_name')
                    ->label('Import From')
                    ->description('Product from', position: 'above')
                    ->color(function (string $state) {
                        if ($state == 'purchase') {
                            return 'info';
                        } else {
                            return 'primary';
                        }
                    }),
                Tables\Columns\TextColumn::make('edited_by')
                    ->icon('heroicon-s-user')
                    ->description(fn (Product $record): string => $record->edited_by == 'Not edited'  ? 'N/A' : date('M D Y H:i', strtotime($record->updated_at)))
                    ->color(function (string $state) {
                        if ($state == 'Not edited')
                            return 'primary';
                        else {
                            return 'warning';
                        }
                    }),
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
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
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
