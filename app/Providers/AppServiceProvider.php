<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BezhanSalleh\PanelSwitch\PanelSwitch;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use BezhanSalleh\FilamentLanguageSwitch\Enums\Placement;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
        //     $switch
        //         ->outsidePanelRoutes([
        //             'dashboard',
        //             '/',
        //             // Additional custom routes where the switcher should be visible outside panels
        //         ])
        //         // ->flags([
        //         //     'ar' => asset('flags/saudi-arabia.svg'),
        //         //     'fr' => asset('flags/france.svg'),
        //         //     'en' => asset('flags/usa.svg'),
        //         // ])
        //         // ->renderHook('panels::global-search.after')
        //         ->visible(outsidePanels: true)
        //         // ->outsidePanelPlacement(Placement::BottomRight)
        //         // ->outsidePanelRoutes(fn () => someCondition() ? ['dynamic.route'] : ['default.route'])
        //         ->locales(['en', 'sw', 'fr'])
        //         ->labels([
        //             'pt_BR' => 'Português (BR)',
        //             'pt_PT' => 'Português (PT)',
        //             // Other custom labels as needed
        //         ]);
        // });
        // PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
        //     $panelSwitch
        //         ->modalHeading('Available Panels')
        //         ->modalWidth('sm')
        //         ->labels([
        //             'shop' => 'Custom Admin Label',
        //             'shop1' => __('General Manager')
        //         ])
        //         // ->renderHook('panels::global-search.after')
        //         ->slideOver();
        // });
    }
}