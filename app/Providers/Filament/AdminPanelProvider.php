<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Blade;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Saade\FilamentLaravelLog\FilamentLaravelLogPlugin;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('shop')
            ->path('shop')
            ->login()
            ->favicon(asset('/storage/images/logo/logo.svg'))
            ->databaseNotifications()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearch(true)
            ->colors([
                'primary' => Color::Green,
                'danger' => Color::Red,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'gray' => Color::Neutral,
            ])
            ->sidebarCollapsibleOnDesktop()
            // ->domain('shop')
            // ->renderHook(
            //     'panels::global-search.before',
            //     fn (): string => Blade::render('@livewire(\'livewire-ui-modal\')'),
            // )
            // ->plugin(
            //     // FilamentLaravelLogPlugin::make()
            //     //     ->navigationGroup('System Tools')
            //     //     ->navigationLabel('Logs')
            //     //     ->navigationIcon('heroicon-o-bug-ant')
            //     //     ->navigationSort(1)
            //     //     ->slug('logs')
            //     //     ->logDirs([
            //     //         storage_path('logs'),     // The default value
            //     //     ])
            //     //     ->excludedFilesPatterns([
            //     //         '*2023*'
            //     //     ])
            //     // ->authorize(
            //     //     fn () => auth()->user()->isAdmin()
            //     // )
            // )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}