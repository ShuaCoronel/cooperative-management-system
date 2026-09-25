@if (session('success') || session('error') || session('info') || session('warning'))

    @php
        $type = 'info';
        $message = '';

        if (session('success')) {
            $type = 'success';
            $message = session('success');
        } elseif (session('error')) {
            $type = 'error';
            $message = session('error');
        } elseif (session('warning')) {
            $type = 'warning';
            $message = session('warning');
        } elseif (session('info')) {
            $type = 'info';
            $message = session('info');
        }

        $styles = [
            'success' => [
                'border' => 'border-green-500',
                'icon_bg' => 'bg-green-100 text-green-600',
                'title' => 'Success',
            ],
            'error' => [
                'border' => 'border-red-500',
                'icon_bg' => 'bg-red-100 text-red-600',
                'title' => 'Error',
            ],
            'warning' => [
                'border' => 'border-amber-500',
                'icon_bg' => 'bg-amber-100 text-amber-600',
                'title' => 'Warning',
            ],
            'info' => [
                'border' => 'border-blue-500',
                'icon_bg' => 'bg-blue-100 text-blue-600',
                'title' => 'Info',
            ],
        ];

        $current = $styles[$type];
    @endphp

    <!-- Top-Center Positioning Container -->
    <div class="fixed top-10 rounded-xl left-1/2 -translate-x-1/2 z-50 max-w-md w-full px-4 transition-all duration-300 transform translate-y-0 opacity-100" id="toast-notification">
        
        <div class="flex items-center gap-3 p-4 bg-white shadow-2xl border-l-4 {{ $current['border'] }}">
            
            <!-- Dynamic Icon -->
            <div class="rounded-full p-2 shrink-0 {{ $current['icon_bg'] }}">
                @if ($type === 'success')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                @elseif ($type === 'error')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                @elseif ($type === 'warning')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @endif
            </div>

            <!-- Text Content -->
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900">{{ $current['title'] }}</p>
                <p class="text-sm text-gray-600 truncate">{{ $message }}</p>
            </div>

            <!-- Close Button (X) -->
            <button type="button" onclick="document.getElementById('toast-notification').remove()" class="text-gray-400 hover:text-gray-600 rounded-lg p-1 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

        </div>
    </div>

    <!-- Auto-Dismiss Script -->
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast-notification');
            if (toast) {
                toast.style.opacity = '0';
                toast.style.transform = 'translate(-50%, -20px)';
                setTimeout(() => toast.remove(), 300);
            }
        }, 4000);
    </script>

@endif