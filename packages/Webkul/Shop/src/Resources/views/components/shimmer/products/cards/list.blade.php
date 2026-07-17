@props(['count' => 0])

@for ($i = 0; $i < $count; $i++)
<div class="grid grid-cols-1 gap-6">
    <div class="relative grid max-w-max grid-cols-2 gap-4 max-sm:grid-cols-1">
        <div class="shimmer relative min-h-64.5 min-w-62.5 overflow-hidden rounded-sm"> 
            <img class="rounded-xs bg-zinc-100">
        </div>

        <div class="grid content-start gap-4">
            <p class="shimmer h-6 w-3/4"></p>

            <p class="shimmer h-6 w-[55%]"></p>

            <!-- Needs to implement that in future -->
            <div class="flex hidden gap-4"> 
                <span class="shimmer block h-8 w-8 rounded-full"></span> 

                <span class="shimmer block h-8 w-8 rounded-full"></span> 
            </div>

            <p class="shimmer h-6 w-full"></p>

            <div class="shimmer h-12 w-42 rounded-xl"></div>
        </div>
    </div>
</div>
@endfor
