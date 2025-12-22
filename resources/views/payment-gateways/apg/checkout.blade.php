<x-app-layout>
    <x-slot:title>
        APG Payment - Processing
    </x-slot:title>

    <div class="max-w-2xl mx-auto py-12">
        <x-card>
            <x-slot:header>
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                        <x-heroicon-o-credit-card class="w-6 h-6 text-emerald-600" />
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-300">
                            APG Payment Gateway
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Redirecting to Bank Alfalah...
                        </p>
                    </div>
                </div>
            </x-slot:header>

            <x-slot:content>
                <div class="text-center py-8">
                    <!-- Loading Spinner -->
                    <div class="flex justify-center mb-6">
                        <svg class="animate-spin h-12 w-12 text-emerald-600" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                        Preparing your payment...
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        You will be redirected to Bank Alfalah's secure payment page
                    </p>

                    <!-- Invoice Details -->
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Invoice:</span>
                            <span
                                class="text-sm font-medium text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</span>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Amount:</span>
                            <span class="text-lg font-bold text-emerald-600">{{ $invoice->formattedTotal() }}</span>
                        </div>
                    </div>

                    <!-- Auto-submit form -->
                    <form id="apgForm" action="{{ $returnUrl }}" method="POST">
                        @csrf
                        <input type="hidden" name="AuthToken" value="{{ $authToken }}">
                        <input type="hidden" name="RequestHash"
                            value="{{ $transaction->request_data['request_hash'] ?? '' }}">

                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">
                            <x-heroicon-o-arrow-right class="w-4 h-4 mr-2" />
                            Continue to Payment
                        </button>
                    </form>

                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-4">
                        If you are not redirected automatically, click the button above
                    </p>
                </div>
            </x-slot:content>
        </x-card>
    </div>

    @push('scripts')
        <script>
            // Auto-submit form after 2 seconds
            setTimeout(function () {
                document.getElementById('apgForm').submit();
            }, 2000);
        </script>
    @endpush
</x-app-layout>