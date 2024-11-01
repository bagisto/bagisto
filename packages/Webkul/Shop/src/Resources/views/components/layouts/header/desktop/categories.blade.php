<div class="flex items-center text-white relative">
    <div class="group flex h-[46px] items-center border-b-[2px] border-transparent hover:border-b-[2px] hover:border-pelorous-700">
        <a href="{{ url('/') }}" class="inline-block px-5 uppercase text-sm">
            Начало
        </a>
    </div>

    @foreach(\Illuminate\Support\Facades\View::shared(core()->getCurrentLocale()->code . '_visible_category_tree_1') as $category)
        <div class="group flex h-[46px] items-center border-b-[2px] border-transparent hover:border-b-[2px] hover:border-pelorous-700">
            <a href="{{ $category['url'] }}" class="inline-block px-5 uppercase text-sm">
                {{ $category['name'] }}
            </a>
            @if ($category['children'])
                <div class="pointer-events-none absolute top-[46px] z-[1] max-h-[580px] w-max max-w-[1260px] translate-y-1 overflow-auto overflow-x-auto border border-b-0 border-l-0 border-r-0 border-t border-[#F3F3F3] bg-white p-9 opacity-0 shadow-[0_6px_6px_1px_rgba(0,0,0,.3)] transition duration-300 ease-out group-hover:pointer-events-auto group-hover:translate-y-0 group-hover:opacity-100 group-hover:duration-200 group-hover:ease-in left-0">
                    @foreach(collect($category['children'])->split(2) as $groupedSubs)
                    <div class="aigns flex justify-between gap-x-[70px]">
                        @foreach($groupedSubs as $subCategory)
                            <div class="grid w-full min-w-max max-w-[150px] flex-auto grid-cols-[1fr] content-start gap-5">
                                <p class="font-medium text-navyBlue">
                                    <a href="{{ $subCategory['url'] }}">{{ $subCategory['name'] }}</a>
                                </p>

                                @if ($subCategory['children'])
                                    <ul class="grid grid-cols-[1fr] gap-3">
                                        @foreach($subCategory['children'] as $subSubCategory)
                                            <li class="text-sm font-medium text-zinc-500">
                                                <a href="{{ $subSubCategory['url'] }}">
                                                    {{ $subSubCategory['name'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</div>
