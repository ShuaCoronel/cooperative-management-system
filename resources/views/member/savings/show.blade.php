<!-- <div>
     Nothing in life is to be feared, it is only to be understood. Now is the time to understand more, so that we may fear less. - Maria Skłodowska-Curie
</div> -->

<app-layout>

    <div class="max-w-7x mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- top navigation header -->
         <div class="mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('member.dashboard')}}" class=" text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center gap-1">
                &larr; Back to Dashboard 
                </a>
            <h1>

                @foreach ( $member->savingsAccounts as $savingsAccount )
                    <p class="text-sm text-gray-500 capitalize">
                    Product: {{ $savingsAccount->product_type }}


                    </p>

                @endforeach
                
            </h1>

            </div>


            

         </div>



    </div>






</app-layout>



