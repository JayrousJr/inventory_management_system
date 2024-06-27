<x-filament-widgets::widget>@if(Auth::user()->role === 'Manager' || Auth::user()->role === 'System Administrator')
    <x-filament::section>
        <div class="px-1 text-center font-normal">
            <a href="{{'/account'}}">View the Accounting Panel
            </a>
        </div>
    </x-filament::section>@endif
</x-filament-widgets::widget>