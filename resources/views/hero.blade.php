
<x-coopguest-layout>
     <x-slot name="header">
        <nav x-data="{ open: false }" class="w-full">
            <!-- Primary Navigation Menu (mirrors layouts/navigation.blade.php) -->
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden sm:flex sm:items-center gap-7">
                    <a href="#"
                       class="text-sm font-medium text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out">
                        Contact
                    </a>

                    <x-primary-button :href="route('login')" class="!bg-blue-500" wire:navigate>
                        Login
                    </x-primary-button>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-200">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="#"
                       class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition duration-150 ease-in-out">
                        Contact
                    </a>

                    <a href="{{ route('login') }}" wire:navigate
                       class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition duration-150 ease-in-out">
                        Login
                    </a>
                </div>
            </div>
        </nav>
    </x-slot>


    <div>
        <p class="font-bold text-5xl text-center text-white"> 
            Manage your cooperative in a neat way!
        </p>

    </div>

    

    
</x-coopguest-layout>