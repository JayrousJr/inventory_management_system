<?php

namespace App\Filament\Shop\Resources\RoleResource\Pages;

use Filament\Actions;
use App\Filament\Shop\Resources\RoleResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Role Edited')
            ->icon('heroicon-o-pencil-square')
            ->iconColor('success')
            ->body('The Role has been changed')
            ->send();
    }
}
