<x-coopguest-layout>
     <x-slot name="header">
        <div class="flex flex-row px-[15%]">
            
                <div class="w-full flex justify-between gap-3">
                    <nav>
                        <x-application-logo class="h-10 text-red-600" />

                    </nav>


                    <nav class="flex items-center gap-7">
                        <a href="">
                            Contact
                        </a>



                        <x-primary-button class="!bg-blue-500">
                            <a href="{{route('login')}}">Login</a>

                        </x-primary-button>
                    </nav>
                </div>
            
            
        </div>
    </x-slot>

    <div>
        <p class="font-bold text-5xl text-center text-white"> 
            Manage your cooperative in a neat way!
        </p>

    </div>

    

    
</x-coopguest-layout>