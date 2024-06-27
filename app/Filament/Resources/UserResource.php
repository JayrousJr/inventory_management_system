<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Filament\Resources\UserResource\RelationManagers\DebtsRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\SalesRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\ProductsRelationManager;

class UserResource extends Resource
{
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationGroup = 'User & Security Management';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('shop_id')
                    ->required()
                    ->label('Shop ID')
                    ->searchable()
                    ->live()
                    ->visible(function () {
                        if (auth()->user()->isManager() || auth()->user()->isAdmin()) {
                            return true;
                        }
                    })
                    ->preload()
                    ->options(Shop::all()->pluck('shop_name', 'id'))
                    ->afterStateUpdated(function ($state, Set $set) {
                        if (blank($state)) return;

                        $shop = Shop::find($state);

                        $set('shop_name', $shop->shop_name);
                    }),
                Select::make('roles')
                    ->label('Role in shop')
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->optionsLimit(4)
                    ->searchable()
                    ->live()
                    ->visible(function () {
                        if (auth()->user()->isManager() || auth()->user()->isAdmin()) {
                            return true;
                        }
                    })
                    ->relationship('roles', 'name')
                    ->preload()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if (blank($state)) return;

                        $role = Role::find($state);

                        $set('role', $role->name);
                    }),
                Forms\Components\Hidden::make('shop_name')
                    ->visible(auth()->user()->isAdmin())
                    ->required()
                    ->visible(auth()->user()->isManager()),
                Forms\Components\Hidden::make('role')
                    ->label('Employeed As'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shop_name')
                    ->color(function (string $state) {
                        if ($state == 'Store One')
                            return 'info';
                        if ($state == 'Store Two')
                            return 'primary';
                        else
                            return 'warning';
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(function (string $state) {
                        if ($state == 'Customer')
                            return 'info';
                        if ($state == 'Manager')
                            return 'primary';
                        if ($state == 'Sales Person')
                            return 'gray';
                        if ($state == 'Stock Person')
                            return 'warning';
                    })
                    ->searchable(),
                // Tables\Columns\TextColumn::make('email')
                //     ->searchable(),
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
            ])->defaultSort('created_at', 'asc')
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
            SalesRelationManager::class,
            DebtsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
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