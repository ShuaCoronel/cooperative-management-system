<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    {{-- <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script> --}}

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>CoopEx</title>
</head>
<body>


    <div class="min-h-screen bg-blue-300 align-middle justify-center">

        <div>
            <h1>
            test

            </h1>
        </div>




        <div>
            <a
                            href="{{ route('login') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] bg-blue-500 text-center text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                        >
                            Log in
            </a>

        </div>




    </div>




    




</body>
</html>