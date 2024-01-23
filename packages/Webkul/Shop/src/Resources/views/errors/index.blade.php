<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang("admin::app.errors.{$errorCode}.title")
    </x-slot>

    <!-- cursor movement canvas line -->
    <canvas 
        resize="true"
        id="canvas-wd"
    >
    </canvas>

    <!-- Error page Information -->
	<div class="container absolute left-1/2 top-0 px-[60px] max-lg:px-8 max-sm:px-4 -translate-x-1/2">
		<div class="grid w-full h-[100vh]">
			<div class="wrapper-404 max-868:!text-[294px] max-md:!text-[140px]">
				<div class="glow-404">
                    {{ $errorCode }}
                </div>

				<div class="glow-shadow-404">
                    {{ $errorCode }}
                </div>

				<div class="absolute left-1/2 top-[74%] -translate-x-1/2 -translate-y-1/2 text-center mt-10 max-868:w-full">
					<h1 class="text-3xl font-semibold">
                        @lang("admin::app.errors.{$errorCode}.title")
                    </h1>

					<p class="text-lg text-[#6E6E6E] mt-4">
                        {{ 
                            $errorCode === 503 && core()->getCurrentChannel()->maintenance_mode_text != ""
                            ? core()->getCurrentChannel()->maintenance_mode_text : trans("admin::app.errors.{$errorCode}.description")
                        }}
                    </p>

					<a 
                        href="{{ route('shop.home.index') }}"
						class="block w-max mt-8 m-auto py-4 px-10 bg-navyBlue rounded-[45px] text-white text-base font-medium text-center cursor-pointer max-sm:text-sm max-sm:px-6 max-sm:mb-10"
                    >
						@lang('shop::app.errors.go-to-home') 
                    </a>
				</div>
			</div>
		</div>
	</div>

    @pushOnce('scripts')
        <script type="text/paperscript" canvas="canvas-wd">
            var points = 30;
            var length = 30;
        
            var path = new Path({
                strokeColor: '#060C3B',
                strokeWidth: 10,
                strokeCap: 'round'
            });
        
            var start = view.center / [10, 1];
            for (var i = 0; i < points; i++)
                path.add(start + new Point(i * length, 0));
        
            function onMouseMove(event) {
                path.firstSegment.point = event.point;
                for (var i = 0; i < points - 1; i++) {
                    var segment = path.segments[i];
                    var nextSegment = segment.next;
                    var vector = segment.point - nextSegment.point;
                    vector.length = length;
                    nextSegment.point = segment.point - vector;
                }
                path.smooth({ type: 'continuous' });
            }
        </script>
    @endPushOnce
</x-shop::layouts>