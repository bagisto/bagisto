@props(['count' => 0])

@for ($i = 0; $i < $count; $i++)
    <div class="flex gap-[20px] rounded-[12px] p-[15px]max-sm:flex-wrap mt-[20px]">
        <div class="flex justify-content:between ml-[2px]">
            <div class="relative overflow-hidden rounded-sm  min-w-[258px] min-h-[250px] bg-[#E9E9E9] shimmer">
                <img class="rounded-sm bg-[#F5F5F5]">
            </div>

            <div class="ml-[20px]">
                <div class="grid gap-2.5 content-start">
                    <p class="w-[200px] h-[24px] bg-[#E9E9E9] shimmer"></p>
                    <p class="w-[150px] h-[24px] bg-[#E9E9E9] shimmer"></p>

                    <div class="flex gap-4 mt-[8px]">
                        <span class="rounded-full w-[30px] h-[30px] block bg-[#E9E9E9] shimmer"></span>
                        <span class="rounded-full w-[30px] h-[30px] block bg-[#E9E9E9] shimmer"></span>
                    </div>

                    <button class="w-[200px] h-[56px] rounded-[12px] bg-[#E9E9E9] shimmer">
                    </button>
                </div>
            </div>
        </div>
    </div>
@endfor
