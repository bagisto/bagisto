<x-shop::layouts
    :has-header="true"
    :has-feature="false"
    :has-footer="false"
>
	<div class="absolute top-[50%] left-[50%] -translate-x-[50%] -translate-y-[50%]">
		<div class="grid gap-y-[20px] place-items-center">
			<img 
				class="" 
				src="{{ bagisto_asset('images/thank-you.png') }}" 
				alt="" 
				title=""
			>

			<p class="text-[20px]">

				@if (auth()->guard('customer')->user())
					@lang('shop::app.checkout.success.order-id-info', [
							'order_id' => '<a class="text-[#0A49A7]" href="' . route('shop.customers.account.orders.view', $order->id) . '">' . $order->increment_id . '</a>'
					])
				@else
					@lang('shop::app.checkout.success.order-id-info', ['order_id' => $order->increment_id]) 
				@endif
			</p>

			<p class="text-[26px] font-medium">
				@lang('shop::app.checkout.success.thanks')
			</p>
			
			<p class="text-[20px] text-[#7D7D7D]">
				@lang('shop::app.checkout.success.info')
			</p>

			{{ view_render_event('bagisto.shop.checkout.continue-shopping.before', ['order' => $order]) }}

			<a href="{{ route('shop.home.index') }}">
				<div class="m-auto block mx-auto bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer">
             		@lang('shop::app.checkout.cart.index.continue-shopping')
				</div> 
			</a>
			
			{{ view_render_event('bagisto.shop.checkout.continue-shopping.after', ['order' => $order]) }}
			
		</div>
	</div>
</x-shop::layouts>