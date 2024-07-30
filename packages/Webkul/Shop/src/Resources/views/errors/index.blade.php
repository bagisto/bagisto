<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang("admin::app.errors.{$errorCode}.title")
    </x-slot>

    <!-- Error page Information -->
	<div class="container absolute left-1/2 top-0 -translate-x-1/2 px-[60px] max-lg:px-8 max-sm:px-4">
		<div class="grid h-[100vh] w-full">
			<div class="wrapper-404 max-868:!text-[294px] max-md:!text-[140px]">
				<div class="glow-404">
                    {{ $errorCode }}
                </div>

				<div class="glow-shadow-404">
                    {{ $errorCode }}
                </div>
			</div>

            <div class="absolute left-1/2 top-[74%] mt-10 -translate-x-1/2 -translate-y-1/2 text-center max-868:w-full max-md:top-[60%]">
                <h1 class="text-3xl font-semibold max-md:text-xl">
                    @lang("admin::app.errors.{$errorCode}.title")
                </h1>

                <p class="mt-4 text-lg text-zinc-500 max-md:text-sm">
                    {{ 
                        $errorCode === 503 && core()->getCurrentChannel()->maintenance_mode_text != ""
                        ? core()->getCurrentChannel()->maintenance_mode_text : trans("admin::app.errors.{$errorCode}.description")
                    }}
                </p>

                <a 
                    href="{{ route('shop.home.index') }}"
                    class="m-auto mt-8 block w-max cursor-pointer rounded-[45px] bg-navyBlue px-10 py-4 text-center text-base font-medium text-white max-sm:mb-10 max-sm:px-6 max-sm:text-sm"
                >
                    @lang('shop::app.errors.go-to-home') 
                </a>
            </div>
		</div>
	</div>
</x-shop::layouts>