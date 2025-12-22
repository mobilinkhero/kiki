<x-app-layout>
    <x-slot:title>
        Redirecting to Payment Gateway
    </x-slot:title>

    <div class="max-w-2xl mx-auto py-20">
        <x-card>
            <x-slot:content>
                <div class="p-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-100 mb-6">
                        <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                        {{ t('redirecting_to_payment_gateway') ?? 'Redirecting to Bank Alfalah...' }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-8">
                        {{ t('please_do_not_refresh_page') ?? 'Please do not refresh the page or click the back button.' }}
                    </p>

                    <form id="paymentForm" action="{{ $paymentUrl }}" method="POST">
                        @foreach($params as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700">
                            {{ t('click_if_not_redirected') ?? 'Click here if you are not redirected' }}
                        </button>
                    </form>
                </div>
            </x-slot:content>
        </x-card>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var form = document.getElementById('paymentForm');
                if (form) {
                    form.submit();
                }
            }, 1000);
        });
    </script>
    @endpush
</x-app-layout>