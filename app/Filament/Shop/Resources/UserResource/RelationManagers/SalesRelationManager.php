<?php

namespace App\Filament\Shop\Resources\UserResource\RelationManagers;

use Filament\Forms;
use App\Models\Sale;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SalesRelationManager extends RelationManager
{
    protected static string $relationship = 'sales';
    protected static bool $isLazy = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('Name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Name')
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
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make(),
            ]);
    }
}
