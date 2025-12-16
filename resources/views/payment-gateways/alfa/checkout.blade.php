<x-app-layout>
    <x-slot:title>
        {{ t('alfa_payment') }}
    </x-slot:title>

    <div class="max-w-xl mx-auto py-12">
        <x-card>
            <x-slot:header>
                <h2 class="text-xl font-bold">{{ t('redirecting_to_alfa') }}</h2>
            </x-slot:header>

            <x-slot:content>
                <div class="text-center">
                    <p class="mb-4">{{ t('please_wait_redirecting') }}</p>
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary-600 mx-auto"></div>

                    <!-- Hidden Form for POSTing to Alfa -->
                    <form id="alfa-form" action="{{ $url }}" method="POST">
                        @foreach($params as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                    </form>
                </div>
            </x-slot:content>
        </x-card>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.getElementById('alfa-form').submit();
            });
        </script>
    @endpush
</x-app-layout>