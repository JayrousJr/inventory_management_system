<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Debt;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\DebtResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DebtResource\RelationManagers;

class DebtResource extends Resource
{
    protected static ?string $model = Debt::class;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static ?string $navigationGroup = 'Sales & Debts Management';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $recordTitleAttribute = 's_or_c_name';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('total_amount')
                    ->required()
                    ->prefix('TSH')
                    ->readOnly()
                    ->numeric(),
                Forms\Components\TextInput::make('paid_amount')
                    ->required()
                    ->live()
                    ->lte('total_amount')
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        $total_amount = $get('total_amount');
                        $paid_amount = $get('paid_amount');

                        $total = floatval($total_amount) - floatval($paid_amount);
                        $set('remaining_amount', $total);
                    })
                    ->prefix('TSH')
                    ->numeric(),
                Forms\Components\TextInput::make('remaining_amount')
                    ->readOnly()
                    ->prefix('TSH')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
            'index' => Pages\ListDebts::route('/'),
            'create' => Pages\CreateDebt::route('/create'),
            'view' => Pages\ViewDebt::route('/{record}'),
            'edit' => Pages\EditDebt::route('/{record}/edit'),
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
