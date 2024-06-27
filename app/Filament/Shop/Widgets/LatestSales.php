<?php

namespace App\Filament\Shop\Widgets;


use App\Models\Sale;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;

class LatestSales extends BaseWidget
{
    protected int | string | array $columnSpan = 'full'; 
    protected static bool $isLazy = false;
    protected static ?string $maxHeight = '250px';
    protected static ?int $sort = 5;
   
    protected function getTableQuery(): Builder|Relation|null
    {
        return Sale::query()->latest();
        // ->where('shop_id', Auth::user()->shop_id);
    }
    protected function getTableColumns(): array
    {
        return [
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
                ->icon('heroicon-s-calculator')
                ->color(function (string $state) {
                    if ($state == '0')
                        return 'danger';
                    else {
                        return 'primary';
                    }
                })
                ->sortable(),
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
        ];
    }
}