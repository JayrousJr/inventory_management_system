<?php

namespace App\Filament\Shop\Resources\UserResource\Pages;

use Filament\Actions;
use App\Filament\Shop\Resources\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('User Created')
            ->icon('heroicon-o-plus')
            ->iconColor('success')
            ->body('New user has been created Successifully');
    }
}
