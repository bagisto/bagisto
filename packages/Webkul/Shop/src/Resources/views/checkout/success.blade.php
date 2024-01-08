<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
		@lang('shop::app.checkout.success.thanks')
    </x-slot>

	<div class="container mt-8 px-[60px] max-lg:px-8">
		<div class="grid gap-y-5 place-items-center">
			{{ view_render_event('bagisto.shop.checkout.success.image.before', ['order' => $order]) }}

			<img 
				class="" 
				src="{{ bagisto_asset('images/thank-you.png') }}" 
				alt="thankyou" 
				title=""
			>

			{{ view_render_event('bagisto.shop.checkout.success.image.after', ['order' => $order]) }}

			<p class="text-xl">
				@if (auth()->guard('customer')->user())
					@lang('shop::app.checkout.success.order-id-info', [
						'order_id' => '<a class="text-[#0A49A7]" href="' . route('shop.customers.account.orders.view', $order->id) . '">' . $order->increment_id . '</a>'
					])
				@else
					@lang('shop::app.checkout.success.order-id-info', ['order_id' => $order->increment_id]) 
				@endif
			</p>

			<p class="text-2xl font-medium">
				@lang('shop::app.checkout.success.thanks')
			</p>
			
			<p class="text-xl text-[#6E6E6E]">
				@if (! empty($order->checkout_message))
					{!! nl2br($order->checkout_message) !!}
				@else
					@lang('shop::app.checkout.success.info')
				@endif
			</p>

			{{ view_render_event('bagisto.shop.checkout.success.continue-shopping.before', ['order' => $order]) }}

			<a href="{{ route('shop.home.index') }}">
				<div class="block w-max mx-auto m-auto py-3 px-11 bg-navyBlue rounded-2xl text-white text-basefont-medium text-center cursor-pointer">
             		@lang('shop::app.checkout.cart.index.continue-shopping')
				</div> 
			</a>
			
			{{ view_render_event('bagisto.shop.checkout.success.continue-shopping.after', ['order' => $order]) }}
		</div>
	</div>
</x-shop::layouts>