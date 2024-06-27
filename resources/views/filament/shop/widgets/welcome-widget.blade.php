<x-filament-widgets::widget>
    <x-filament::section>
        <p class="px-1 text-center font-normal">
            Welcome To {{env('APP_NAME')}} <span class="font-semibold text-red-600">
                {{Auth::user()->shop_name}}</span><br>
            Logged in as <span class="font-semibold text-red-600">{{Auth::user()->role}}</span>
        </p>
    </x-filament::section>
</x-filament-widgets::widget>