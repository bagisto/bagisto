@if ($paginator->hasPages())
    <div class="flex items-center justify-between p-6">
        <p class="text-xs font-medium">
            @lang('shop::app.partials.pagination.pagination-showing', [
                'firstItem' => $paginator->firstItem(),
                'lastItem' => $paginator->lastItem(),
                'total' => $paginator->total(),
            ])
        </p>

        <nav aria-label="Page Navigation">
            <ul class="inline-flex items-center -space-x-px">
                <!-- Previous Page Link -->
                <li>
                    @if ($paginator->onFirstPage())
                        <span class="icon-arrow-left rtl:icon-arrow-right flex h-[37px] w-[35px] items-center justify-center border border-zinc-200 text-2xl font-medium leading-normal ltr:rounded-l-lg rtl:rounded-r-lg"></span>
                    @else
                        <a 
                            href="{{ urldecode($paginator->previousPageUrl()) }}" 
                            class="flex h-[37px] w-[35px] items-center justify-center border border-zinc-200 font-medium leading-normal hover:bg-gray-100 ltr:rounded-l-lg rtl:rounded-r-lg" 
                            aria-label="Previous Page"
                        >
                            <span class="icon-arrow-left rtl:icon-arrow-right text-2xl"></span>
                        </a>
                    @endif
                </li>

    
                <!-- Pagination Elements -->
                @foreach ($elements as $element) 
                    @if (is_string($element)) 
                        <li>
                            <span 
                                class="disabled flex h-[37px] w-[35px] items-center justify-center border border-zinc-200 text-center font-medium leading-normal text-black"
                            >
                                {{ $element }}
                            </span>
                        </li>
                    @endif 

                    @if (is_array($element)) 
                        @foreach ($element as $page => $url) 
                            <li>
                                @if ($page == $paginator->currentPage()) 
                                        <a 
                                            href="#" 
                                            class="flex h-[37px] w-[35px] items-center justify-center border border-zinc-200 bg-gray-100 text-center font-medium leading-normal text-black hover:bg-gray-100"
                                        >
                                        {{ $page }}
                                        </a>
                                @else 
                                    <a 
                                        href="{{ $url }}"
                                        class="flex h-[37px] w-[35px] items-center justify-center border border-zinc-200 text-center font-medium leading-normal text-black hover:bg-gray-100"
                                    >
                                    {{ $page }}
                                    </a>
                                @endif 
                            </li>
                        @endforeach 
                    @endif 
                @endforeach

                <!-- Next Page Link -->
                <li>
                    @if ($paginator->hasMorePages())
                        <a 
                            href="{{ urldecode($paginator->nextPageUrl()) }}" 
                            class="flex h-[37px] w-[35px] items-center justify-center border border-zinc-200 font-medium leading-normal hover:bg-gray-100 ltr:rounded-r-lg rtl:rounded-l-lg" 
                            aria-label="Next Page"
                        >
                            <span class="icon-arrow-right rtl:icon-arrow-left text-2xl"></span>
                        </a>
                    @else
                        <span class="icon-arrow-right rtl:icon-arrow-left flex h-[37px] w-[35px] items-center justify-center border border-zinc-200 text-2xl font-medium leading-normal ltr:rounded-r-lg rtl:rounded-l-lg"></span>
                    @endif
                </li>
            </ul>
        </nav>
    </div>
@endif
