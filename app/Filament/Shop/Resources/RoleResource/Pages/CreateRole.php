<?php

namespace App\Filament\Shop\Resources\RoleResource\Pages;

use Filament\Actions;
use App\Filament\Shop\Resources\RoleResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Role Created')
            ->icon('heroicon-o-plus')
            ->iconColor('success')
            ->body('New Role has been created Successifully');
    }
}
