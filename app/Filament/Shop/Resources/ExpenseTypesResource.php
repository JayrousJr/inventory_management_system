<?php

namespace App\Filament\Shop\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ExpenseTypes;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Fieldset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ExpenseTypesResource\Pages;
use App\Filament\Resources\ExpenseTypesResource\RelationManagers;

class ExpenseTypesResource extends Resource
{
    protected static ?string $model = ExpenseTypes::class;

    protected static ?string $navigationIcon = 'heroicon-s-clipboard-document-list';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static ?string $navigationGroup = 'Expenses';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'expense_type';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Expenses')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('expense_type')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('expense_cost')
                                    ->required()
                                    ->prefix('Tsh')
                                    ->numeric(),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('expense_type')
                    ->icon('heroicon-o-wallet')
                    ->color('primary')
                    ->label('Expense Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('expense_cost')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('primary')
                    ->money('Tsh')
                    ->label('Expense Name')
                    ->searchable(),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListExpenseTypes::route('/'),
            'create' => Pages\CreateExpenseTypes::route('/create'),
            'view' => Pages\ViewExpenseTypes::route('/{record}'),
            'edit' => Pages\EditExpenseTypes::route('/{record}/edit'),
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
