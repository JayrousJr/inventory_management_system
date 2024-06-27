<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use App\Models\Debt;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class DebtsRelationManager extends RelationManager
{
    protected static string $relationship = 'debts';
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
                Tables\Columns\TextColumn::make('s_or_c_name')
                    ->label('Customer')
                    ->icon('heroicon-m-user-circle')
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->money('Tsh')
                    ->description(fn (Debt $record): string => 'Paid: Tsh ' . number_format((float)$record->paid_amount, 0, '.', ','), position: 'below')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('remaining_amount')
                    ->numeric()
                    ->money('Tsh')
                    ->color(function (string $state) {
                        if ($state == '0')
                            return 'primary';
                        else {
                            return 'danger';
                        }
                    })
                    ->description(fn (Debt $record): string => 'From: ' . $record->source_name, position: 'below')
                    ->label('Debt'),
                Tables\Columns\TextColumn::make('debt_type')
                    ->badge()
                    ->label('Type')
                    ->color(function (string $state) {
                        if ($state == 'Creditor')
                            return 'primary';
                        else {
                            return 'warning';
                        }
                    })
                    ->numeric(),
                Tables\Columns\TextColumn::make('edited_by')
                    ->icon('heroicon-s-user')
                    ->description(fn (Debt $record): string => $record->edited_by == NULL  ? 'N/A' : date('M D Y H:i', strtotime($record->updated_at)))
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
