<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Expense;
use Filament\Forms\Form;
use Nette\Schema\Expect;
use Filament\Tables\Table;
use App\Models\ExpenseTypes;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\ActionSize;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ExpenseResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ExpenseResource\RelationManagers;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static ?string $navigationGroup = 'Expenses';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'expense_type';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make()
                    ->schema([
                        Grid::make()
                            ->schema([
                                Forms\Components\Hidden::make('user_id')
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->default(Auth::user()->id),
                                Forms\Components\Hidden::make('shop_id')
                                    ->default(Auth::user()->shop_id)
                                    ->required(fn (string $operation): bool => $operation === 'create'),
                                Forms\Components\Select::make('expense')
                                    ->required()
                                    ->label('Expense')
                                    ->hidden(fn (string $operation): bool => $operation === 'edit')
                                    ->hidden(fn (string $operation): bool => $operation === 'view')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->options(ExpenseTypes::all()->pluck('expense_type', 'id'))
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if (blank($state)) return;
                                        $exp = ExpenseTypes::find($state);
                                        $set('expense_type', $exp->expense_type);
                                        $set('total_amount', $exp->expense_cost);
                                    }),
                                Forms\Components\Hidden::make('expense_type')
                                    ->required()
                                    ->label('Expence Cost'),
                                Forms\Components\TextInput::make('total_amount')
                                    ->required()
                                    ->numeric()
                                    ->readOnly()
                                    ->prefix('Tsh')
                                    ->label('Expence Cost'),
                                Forms\Components\TextInput::make('paid_amount')
                                    ->label('Paid Amount')
                                    ->required()
                                    ->numeric()
                                    ->lte('total_amount')
                                    ->prefix('Tsh')
                                    ->live()
                                    ->afterStateUpdated(function (callable $set, callable $get) {
                                        $total = floatval($get('total_amount'));
                                        $paid = floatval($get('paid_amount'));
                                        $debt = $total - $paid;
                                        $set('unpaid_amount', $debt);
                                    }),
                                Forms\Components\TextInput::make('unpaid_amount')
                                    ->required()
                                    ->readOnly()
                                    ->label('Debt')
                                    ->numeric()
                                    ->readOnly()
                                    ->prefix('Tsh'),
                                Forms\Components\Hidden::make('created_by')
                                    ->required()
                                    ->default(Auth::user()->name),
                                Forms\Components\Hidden::make('updated_by'),
                                Forms\Components\Hidden::make('debt_type')
                                    ->default('Debtor'),
                                Forms\Components\Hidden::make('source')
                                    ->default('expenses'),
                            ])
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('expense_type')
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('Tsh')
                    ->icon('heroicon-s-calculator')
                    ->color(function (string $state) {
                        if ($state == '0')
                            return 'danger';
                        else {
                            return 'primary';
                        }
                    }),
                Tables\Columns\TextColumn::make('unpaid_amount')
                    ->numeric()
                    ->label('Unpaid Amount')
                    ->money('Tsh')
                    ->description(fn (Expense $record): string => $record->unpaid_amount == 0 ? 'No Debt' : 'Under Debt')
                    ->color(function (string $state) {
                        if ($state != '0')
                            return 'danger';
                        else {
                            return 'primary';
                        }
                    }),
                Tables\Columns\TextColumn::make('created_by')
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_by')
                    ->icon('heroicon-s-user')
                    ->description(fn (Expense $record): string => $record->created_at == $record->updated_at  ? 'N/A' : date('M D Y H:i', strtotime($record->updated_at)))
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
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'view' => Pages\ViewExpense::route('/{record}'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
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
