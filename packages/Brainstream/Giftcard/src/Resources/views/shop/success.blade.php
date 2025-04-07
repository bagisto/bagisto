<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
		@lang('giftcard::app.giftcard.thanks')
    </x-slot>

	<div class="container mt-8 px-[60px] max-lg:px-8">
		<div class="grid gap-y-5 place-items-center">

			<img 
				class="" 
				src="{{ bagisto_asset('images/thank-you.png') }}" 
				alt="thankyou" 
				title=""
			>

			<p class="text-4xl font-medium">
				@lang('giftcard::app.giftcard.thanks')
			</p><br>

			<p class="text-2xl font-medium" style="color: rgb(0, 0, 0)">
				We are confident you will be back for more purchase!<br><br>

				Shortly, You will recieve the Giftcard Details via mail.
			</p>

			<a href="{{ route('shop.home.index') }}">
				<div class="block w-max mx-auto m-auto py-3 px-11 bg-navyBlue rounded-2xl text-white text-basefont-medium text-center cursor-pointer mt-4">
             		@lang('giftcard::app.giftcard.continue-shopping')
				</div> 
			</a>

		</div>
	</div>
</x-shop::layouts>