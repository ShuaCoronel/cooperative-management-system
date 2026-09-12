<div>
    @if (session('success'))
        <!-- Overlay -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 backdrop-blur-sm p-4">
            
            <!-- Alert Box -->
            <div class="relative w-full max-w-md rounded-xl border border-green-200 bg-white p-6 text-center shadow-2xl transition-all" role="alert">

                <!-- Stacked & Centered Content -->
                <div class="flex flex-col items-center">
                    
                    <!-- Text Success -->
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Success</h3>

                    <!-- Success Icon check -->
                    <div class="rounded-full bg-green-100 p-3 text-green-600">
                        <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                    </div>

                </div>

                <!-- okay button to close -->
                <div class="mt-2 flex justify-center">
                    <button type="button" onclick="this.closest('.fixed').remove()" class="w-full max-w-xs rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                        OK
                    </button>
                </div>
            </div>

        </div>
    @endif
</div>