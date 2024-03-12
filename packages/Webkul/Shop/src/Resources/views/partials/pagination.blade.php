@if ($paginator->hasPages())
    <div class="flex justify-between items-center p-6">
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
                        <span class="icon-arrow-left rtl:icon-arrow-right text-2xl flex items-center justify-center w-[35px] h-[37px] border border-[#E9E9E9] ltr:rounded-l-lg rtl:rounded-r-lg leading-normal font-medium"></span>
                    @else
                        <a 
                            href="{{ urldecode($paginator->previousPageUrl()) }}" 
                            class="flex items-center justify-center w-[35px] h-[37px] border border-[#E9E9E9] ltr:rounded-l-lg rtl:rounded-r-lg leading-normal font-medium hover:bg-gray-100" 
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
                                class="flex items-center justify-center w-[35px] h-[37px] border border-[#E9E9E9] leading-normal font-medium text-center text-black disabled"
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
                                            class="flex items-center justify-center w-[35px] h-[37px] border border-[#E9E9E9] leading-normal font-medium text-center text-black bg-gray-100 hover:bg-gray-100"
                                        >
                                        {{ $page }}
                                        </a>
                                @else 
                                    <a 
                                        href="{{ $url }}"
                                        class="flex items-center justify-center w-[35px] h-[37px] border border-[#E9E9E9] leading-normal font-medium text-center text-black hover:bg-gray-100"
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
                            class="flex items-center justify-center w-[35px] h-[37px] border border-[#E9E9E9] rtl:rounded-l-lg ltr:rounded-r-lg leading-normal font-medium hover:bg-gray-100" 
                            aria-label="Next Page"
                        >
                            <span class="icon-arrow-right rtl:icon-arrow-left text-2xl"></span>
                        </a>
                    @else
                        <span class="icon-arrow-right rtl:icon-arrow-left text-2xl flex items-center justify-center w-[35px] h-[37px] border border-[#E9E9E9] rtl:rounded-l-lg ltr:rounded-r-lg leading-normal font-medium"></span>
                    @endif
                </li>
            </ul>
        </nav>
    </div>
@endif
