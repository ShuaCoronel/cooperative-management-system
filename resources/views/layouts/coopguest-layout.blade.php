<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col w-full">
         {{-- bg-cover bg-center bg-fixed" style="background-image: url('{{ asset('images/temp-coop-bg.jpg') }}');    --}}

            <div class="mt-10 fixed inset-0 -z-10 pointer-events-none select-none overflow-hidden">
            <img src="{{ asset('images/temp-coop-bg.jpg') }}" 
                 class="w-full h-full object-cover object-center opacity-70 brightness-50" 
                 alt="" />
            </div>


            @isset($header)
                <header class="bg-white shadow w-full">
                    <div class="max-w-7xl h-16 mx-auto px-4 sm:px-6 lg:px-8 items-center flex justify b">
                        {{ $header }}
                    </div>
                </header>
                
            @endisset

            {{-- <div>
                
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
                
            </div> --}}

            <div class="w-full md:max-w-5xl mt-10 px-6 py-4 bg-transparent overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
