<x-app-layout>
    <x-slot:title>
        APG Payment
    </x-slot:title>

    <div class="max-w-5xl mx-auto">
        <x-card>
            <!-- Enhanced Header Section -->
            <x-slot:header>
                <div class="flex items-center space-x-3">
                    <div class="w-6 h-6 sm:w-10 sm:h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                        <x-heroicon-o-credit-card class="w-6 h-6 text-emerald-600" />
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-300">
                            APG Payment Gateway
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-300">
                            Complete your purchase with Bank Alfalah
                        </p>
                    </div>
                </div>
            </x-slot:header>

            <x-slot:content>
                <!-- Main Content -->
                <div class="space-y-6">
                    <!-- Invoice Details Panel -->
                    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden shadow sm:rounded-lg"
                        x-data="{ expanded: true }">
                        <div class="flex items-center justify-between px-4 py-5 sm:px-6 bg-primary-50 dark:bg-slate-700 cursor-pointer"
                            @click="expanded = !expanded">
                            <div class="flex items-center">
                                <x-heroicon-s-receipt-refund class="h-6 w-6 text-gray-600 dark:text-gray-400 mr-3" />
                                <h2 class="text-lg font-medium text-gray-900 dark:text-slate-200">
                                    {{ t('invoice_details') }}
                                </h2>
                            </div>
                            <div class="flex items-center">
                                <span class="mr-3 text-sm font-semibold text-primary-600 dark:text-slate-200">
                                    {{ $invoice->formattedTotal() }}
                                </span>
                                <x-heroicon-s-chevron-down x-show="!expanded" class="h-5 w-5 text-gray-500" />
                                <x-heroicon-s-chevron-up x-show="expanded" class="h-5 w-5 text-gray-500" />
                            </div>
                        </div>

                        <div x-show="expanded" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0">
                            <dl class="divide-y divide-gray-200 dark:divide-slate-700">
                                <div class="px-4 py-4 sm:px-6 grid grid-cols-2">
                                    <dt class="text-sm font-medium text-gray-500 text-left">{{ t('invoice_number') }}
                                    </dt>
                                    <dd class="text-sm text-gray-900 dark:text-slate-200 text-right">
                                        {{ $invoice->invoice_number ?? format_draft_invoice_number() }}
                                    </dd>
                                </div>

                                <div class="px-4 py-4 sm:px-6 sm:grid sm:grid-cols-2">
                                    <dt class="text-sm font-medium text-gray-500 text-left">{{ t('description') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 text-right">
                                        {{ $invoice->title }}
                                    </dd>
                                </div>

                                <div class="px-4 py-4 sm:px-6 sm:grid sm:grid-cols-2">
                                    <dt class="text-sm font-medium text-gray-500 text-left">{{ t('subtotal') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-slate-200 sm:mt-0 text-right">
                                        {{ $invoice->formatAmount($invoice->subTotal()) }}
                                    </dd>
                                </div>

                                <div class="px-4 py-4 sm:px-6 sm:grid sm:grid-cols-2 bg-gray-50 dark:bg-slate-800">
                                    <dt class="text-sm font-medium text-gray-900 dark:text-slate-500 text-left">
                                        {{ t('total_amount') }}
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm font-bold text-primary-600 dark:text-slate-200 sm:mt-0 text-right">
                                        {{ $invoice->formattedTotal() }}
                                    </dd>
                                </div>

                                @if ($remainingCredit > 0)
                                    <div class="px-4 py-4 sm:px-6 sm:grid sm:grid-cols-2 dark:bg-slate-800">
                                        <dt class="text-sm font-medium text-gray-900 dark:text-slate-500 text-left">
                                            {{ t('total_credit_remaining') }}
                                        </dt>
                                        <dd
                                            class="mt-1 text-sm font-bold text-primary-600 dark:text-slate-200 sm:mt-0 text-right">
                                            -{{ $invoice->formatAmount($remainingCredit) }}
                                        </dd>
                                    </div>
                                    <div class="px-4 py-4 sm:px-6 sm:grid sm:grid-cols-2 bg-gray-50 dark:bg-slate-800">
                                        <dt class="text-sm font-medium text-gray-900 dark:text-slate-500 text-left">
                                            {{ t('final_payable_amount') }}
                                        </dt>
                                        <dd
                                            class="mt-1 text-sm font-bold text-primary-600 dark:text-slate-200 sm:mt-0 text-right">
                                            {{ $invoice->formatAmount($invoice->total - $remainingCredit) }}
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Coupon Form Panel -->
                    @include('partials.coupon-form', ['invoice' => $invoice])

                    <!-- Payment Information Panel -->
                    <div
                        class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden shadow sm:rounded-lg">
                        <div class="px-4 bg-emerald-50 py-5 dark:bg-slate-700 sm:px-6">
                            <div class="flex items-center">
                                <x-heroicon-s-credit-card class="h-6 w-6 text-emerald-600 mr-3" />
                                <h2 class="text-lg font-medium text-gray-900 dark:text-slate-200">
                                    Payment Gateway Information
                                </h2>
                            </div>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <x-heroicon-o-information-circle class="h-5 w-5 text-emerald-500" />
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-gray-700 dark:text-slate-300">
                                            You will be redirected to Bank Alfalah's secure payment gateway to complete
                                            your transaction.
                                        </p>
                                    </div>
                                </div>

                                <div class="bg-emerald-50 dark:bg-slate-700/50 rounded-lg p-4">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-slate-200 mb-2">Supported
                                        Payment Methods:</h3>
                                    <ul class="space-y-2">
                                        <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <x-heroicon-o-check-circle class="w-4 h-4 text-emerald-500 mr-2" />
                                            Alfa Wallet
                                        </li>
                                        <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <x-heroicon-o-check-circle class="w-4 h-4 text-emerald-500 mr-2" />
                                            Alfalah Bank Account
                                        </li>
                                        <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <x-heroicon-o-check-circle class="w-4 h-4 text-emerald-500 mr-2" />
                                            Credit/Debit Cards (Visa, MasterCard)
                                        </li>
                                    </ul>
                                </div>

                                <div class="bg-blue-50 dark:bg-slate-700/30 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <x-heroicon-s-shield-check class="h-5 w-5 text-blue-500 mt-0.5 mr-2" />
                                        <div class="text-sm text-blue-700 dark:text-blue-300">
                                            <p class="font-medium">Secure Payment</p>
                                            <p class="mt-1">Your payment information is encrypted and secure. Bank
                                                Alfalah uses industry-standard security measures.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden form for APG redirect -->
                            <form id="apgForm" action="{{ $returnUrl }}" method="POST" class="mt-6">
                                @csrf
                                <input type="hidden" name="AuthToken" value="{{ $authToken }}">
                                <input type="hidden" name="RequestHash"
                                    value="{{ $transaction->request_data['request_hash'] ?? '' }}">

                                <div class="flex items-center justify-between">
                                    <a href="{{ url()->previous() }}"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-slate-700 dark:text-slate-200 dark:border-slate-600 dark:hover:bg-slate-600">
                                        <x-heroicon-o-arrow-left class="w-4 h-4 mr-2" />
                                        {{ t('back') }}
                                    </a>

                                    <button type="submit"
                                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                        {{ t('proceed_to_payment') ?? 'Proceed to Payment' }}
                                        <x-heroicon-o-arrow-right class="w-5 h-5 ml-2" />
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Help Section -->
                    <div class="rounded-md bg-gray-50 p-4 shadow-sm dark:bg-slate-700">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <x-heroicon-o-light-bulb class="h-5 w-5 text-gray-400" />
                            </div>
                            <div class="ml-3 flex-1 md:flex md:justify-between">
                                <p class="text-sm text-gray-400">
                                    {{ t('need_assistance_with_payment') }}
                                </p>
                                <p class="mt-3 text-sm md:mt-0 md:ml-6">
                                    <a href="{{ tenant_route('tenant.tickets.index') }}"
                                        class="whitespace-nowrap font-medium text-primary-600 dark:text-primary-500 hover:text-primary-500">
                                        {{ t('contact_support') }} <span aria-hidden="true">&rarr;</span>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-slot:content>
        </x-card>
    </div>
</x-app-layout>