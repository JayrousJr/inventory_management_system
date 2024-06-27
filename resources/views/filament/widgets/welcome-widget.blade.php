<x-filament-widgets::widget>
    <x-filament::section>


        @if(Auth::user()->role === 'Manager' || Auth::user()->role === 'System Administrator')
        <p class="px-1 text-center font-normal">
            Hello <span class="font-semibold text-red-600">{{Auth::user()->role}}!</span> you view, <span
                class="font-semibold text-red-600">
                {{Auth::user()->shop_name}}</span> <br><a href="{{route('dashboard')}}" class="text-slate-400">click
                here to switch shop </a>
        </p>

        @else

        <p class="px-1 text-center font-normal">
            Hello dear, Welcome To {{env('APP_NAME')}} <span class="font-bold bg-white"></span><br>
            You are Logged in as <span class="font-bold text-muted">{{Auth::user()->role}}</span>
        </p>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>