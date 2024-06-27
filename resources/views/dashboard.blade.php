<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div
                    class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    <!-- <x-application-logo class="block h-12 w-auto" /> -->

                    <h1 class="mt-8 text-2xl font-medium text-gray-900 dark:text-white">
                        Welcome to {{env('APP_NAME')}} Shops Management System!
                    </h1>
                </div>
                <div
                    class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 grid grid-cols-1 md:grid-cols-1 gap-6 lg:gap-8 p-6 lg:p-8">
                    <div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                class="w-6 h-6 stroke-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                            <h2 class="ml-3 text-xl font-semibold text-gray-900 dark:text-white">
                                <a href="">{{env('APP_NAME')}}</a>
                            </h2>
                        </div>

                        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                            Welcome to the {{env('APP_NAME')}}, You can set your account credentials and details in the
                            left upper
                            corner. In the bottom link to visit Admin Dashboard.
                        </p>

                        <p class="mt-4 text-sm">


                            @if(Auth::user()->role ==='Sales Person' || Auth::user()->role === 'Stock Person')
                            <a href="{{'/shop'}}"
                                class="inline-flex items-center font-semibold text-indigo-700 dark:text-indigo-300">
                                Visit Shop
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    class="ml-1 w-5 h-5 fill-indigo-500 dark:fill-indigo-200">
                                    <path fill-rule="evenodd"
                                        d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                            @endif
                            @if(Auth::user()->role === 'Manager' || Auth::user()->role === 'System Administrator')

                            @foreach($shops as $shop)
                        <div>
                            <a href="{{route('select.shop',['shopId' => $shop->id])}}"
                                class="inline-flex items-center font-semibold text-indigo-700 dark:text-indigo-300">
                                Visit {{$shop->shop_name}}
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    class="ml-1 w-5 h-5 fill-indigo-500 dark:fill-indigo-200">
                                    <path fill-rule="evenodd"
                                        d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                        @endforeach
                        @endif
                        </p>
                    </div>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>