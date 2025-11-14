@props(['count' => 0])

@for ($i = 0;  $i < $count; $i++)
	<div>
		<div class="flex gap-5 mt-2">
			<x-shop::media.images.lazy
				class="h-[95px] max-h-[95px] w-28 min-w-32 max-w-24 rounded-xl max-md:w-18 max-md:min-w-18"
				alt="Review Image"
			/>
			
			<div>
				<div class="shimmer w-32 min-w-32 h-4 mt-1"></div>
				<div class="shimmer w-32 min-w-32 h-4 mt-1"></div>
				<div class="shimmer w-32 min-w-32 h-4 mt-1"></div>
				<div class="shimmer w-32 min-w-32 h-4 mt-1"></div>
			</div>
		</div>
	</div>
@endfor