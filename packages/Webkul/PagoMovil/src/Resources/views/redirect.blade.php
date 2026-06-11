<x-shop::layouts>
    <div class="container px-5 py-10 mx-auto">
        <div class="flex flex-col items-center">
            <h1 class="text-3xl font-bold mb-5">@lang('pagomovil::app.shop.payment.redirect.title')</h1>
            
            <div class="w-full max-w-lg bg-white p-8 border border-gray-200 rounded-lg shadow-sm">
                <div class="mb-6 pb-6 border-b border-gray-100">
                    <h2 class="text-xl font-semibold mb-4 text-navyBlue">@lang('pagomovil::app.shop.payment.redirect.payment-data'):</h2>
                    <div class="space-y-2 text-gray-700">
                        <p><strong>@lang('pagomovil::app.shop.payment.redirect.bank'):</strong> {{ $bank }}</p>
                        <p><strong>@lang('pagomovil::app.shop.payment.redirect.phone'):</strong> {{ $phone }}</p>
                        <p><strong>@lang('pagomovil::app.shop.payment.redirect.id-number'):</strong> {{ $id_number }}</p>
                        
                        <div class="mt-4 p-4 bg-blue-50 text-blue-800 rounded-xl border border-blue-100">
                            <p class="text-sm uppercase tracking-wider font-semibold mb-1">
                                @lang('pagomovil::app.shop.payment.redirect.amount-to-transfer'):
                            </p>
                            <p class="text-2xl font-black">
                                {{ core()->formatPrice($amountInVES, 'VES') }}
                            </p>
                            @if(core()->getCurrentCurrencyCode() != 'VES')
                                <p class="mt-2 text-xs opacity-75 italic">
                                    * @lang('pagomovil::app.shop.payment.redirect.amount-info', ['amount' => core()->currency($cart->grand_total)])
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <form action="{{ route('pagomovil.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">@lang('pagomovil::app.shop.payment.redirect.reference')</label>
                        <input type="text" name="reference" class="w-full p-2 border border-gray-300 rounded focus:ring-navyBlue focus:border-navyBlue" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">@lang('pagomovil::app.shop.payment.redirect.bank-origin')</label>
                        <input type="text" name="bank_origin" class="w-full p-2 border border-gray-300 rounded focus:ring-navyBlue focus:border-navyBlue" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">@lang('pagomovil::app.shop.payment.redirect.phone-origin')</label>
                        <input type="text" name="phone_origin" class="w-full p-2 border border-gray-300 rounded focus:ring-navyBlue focus:border-navyBlue" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">@lang('pagomovil::app.shop.payment.redirect.id-number-origin')</label>
                        <input type="text" name="id_number_origin" class="w-full p-2 border border-gray-300 rounded focus:ring-navyBlue focus:border-navyBlue" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">@lang('pagomovil::app.shop.payment.redirect.payment-date')</label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-full p-2 border border-gray-300 rounded focus:ring-navyBlue focus:border-navyBlue" required>
                    </div>

                    <button type="submit" class="w-full bg-navyBlue text-white font-bold py-3 px-4 rounded-xl hover:bg-black transition-all">
                        @lang('pagomovil::app.shop.payment.redirect.submit-button')
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-shop::layouts>