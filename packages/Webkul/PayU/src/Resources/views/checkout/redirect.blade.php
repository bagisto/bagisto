<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('payu::app.redirecting') }}</title>
    
    <link rel="stylesheet" href="{{ bagisto_asset('css/app.css', 'shop') }}">
</head>
<body class="bg-gradient-to-br from-blue-500 to-purple-600">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="rounded-lg bg-white p-8 shadow-xl">
                {!! view_render_event('bagisto.shop.payu.redirect.before') !!}

                <div class="flex justify-center">
                    <div class="h-16 w-16 animate-spin rounded-full border-4 border-gray-200 border-t-blue-600"></div>
                </div>

                <div class="mt-6 text-center">
                    <h2 class="text-2xl font-semibold text-gray-800 max-md:text-xl">
                        {{ __('payu::app.redirecting-to-payment') }}
                    </h2>
                    
                    <p class="mt-2 text-base text-gray-600 max-md:text-sm">
                        {{ __('payu::app.please-wait') }}
                    </p>
                </div>

                <div class="mt-6 flex items-center justify-center gap-2 rounded-lg bg-green-50 p-3">
                    <span class="icon-checkmark text-xl text-green-600"></span>
                    <p class="text-sm font-medium text-green-800">
                        {{ __('payu::app.secure-payment') }}
                    </p>
                </div>

                <p class="mt-4 text-center text-sm text-gray-500">
                    {{ __('payu::app.redirect-message') }}
                </p>

                {!! view_render_event('bagisto.shop.payu.redirect.after') !!}
            </div>
        </div>
    </div>

    <form 
        action="{{ $paymentUrl }}" 
        id="payu_payment_form" 
        method="POST"
        class="hidden"
    >
        @foreach ($paymentData as $name => $value)
            <input
                type="hidden"
                name="{{ $name }}"
                value="{{ $value }}"
            />
        @endforeach

        <button 
            type="submit" 
            class="primary-button rounded-lg px-6 py-3"
        >
            {{ __('payu::app.click-if-not-redirected') }}
        </button>
    </form>

    <script type="text/javascript">
        // Using a small timeout provides extra assurance the form is ready
        setTimeout(function() {
            document.getElementById('payu_payment_form').submit();
        }, 100); // 100 milliseconds delay
    </script>
</body>
</html>