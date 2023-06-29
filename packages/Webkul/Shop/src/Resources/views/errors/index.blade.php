@props([
    'error_code' => $statusCode ?? 404,
    'title' =>  $title ?? trans('shop::app.error.page-lost'),
    'description' => trans('shop::app.error.description')
])

<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <canvas resize="true" id="canvas-wd"></canvas>

	<div class="container px-[60px] max-lg:px-[30px] max-sm:px-[15px] absolute left-[50%] top-0 -translate-x-[50%]">
		<div class="grid h-[100vh] w-full">
			<div class="wrapper-404 max-868:!text-[294px] max-md:!text-[140px]">
				<div class="glow-404">
                    {{ $error_code }}
                </div>

				<div class="glow-shadow-404">
                    {{ $error_code }}
                </div>

				<div class=" absolute left-[50%] top-[74%] -translate-x-[50%] -translate-y-[50%] text-center mt-[40px] max-868:w-full">
					<h1 class="text-[30px] font-semibold">
                        {{ $title }}
                    </h1>

					<p class="text-[18px] text-[#7D7D7D] mt-[15px]">
                        {{ $description }}
                    </p>

					<a 
                        href="{{ route('shop.home.index') }}"
						class="block bg-navyBlue text-white text-base w-max mt-[30px] m-auto font-medium py-[15px] px-[40px] rounded-[45px] text-center cursor-pointer max-sm:text-[14px] max-sm:px-[25px] max-sm:mb-[40px]"
                    >
						@lang('shop::app.error.home') 
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