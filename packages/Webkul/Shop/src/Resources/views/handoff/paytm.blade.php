<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
	<div class="flex flex-col items-center justify-center min-h-screen">
        <div class="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>

        <h2 class="mt-6 text-xl font-semibold text-gray-700">
            {{ trans('paytm::app.shop.payment.redirecting') }}
        </h2>
        <p class="mt-2 text-gray-500 text-sm">
            Please do not refresh or close this window.
        </p>

		<p class="mt-5">
			<img
				class="h-8 w-8 animate-spin text-navyBlue"
				src="{{ bagisto_asset('images/spinner.svg') }}"
				alt="Loading"
			/>
		</p>

        <form action="{{ $paytmUrl }}" id="paytm_checkout" method="POST" class="hidden">
            <input value="{{ trans('paytm::app.shop.payment.redirect-fallback') }}" type="submit">
            @foreach ($paytmFields as $name => $value)
                <input type="hidden" name="{{ $name }}" value="{{ $value }}" />
            @endforeach
        </form>
    </div>

    <script type="text/javascript">
        setTimeout(function() {
            document.getElementById('paytm_checkout').submit();
        }, 500);
    </script>
</x-shop::layouts>
