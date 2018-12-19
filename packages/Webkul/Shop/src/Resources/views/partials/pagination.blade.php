@if ($paginator->hasPages())
    <div class="pagination shop mt-50">
        {{-- Previous Page Link --}}
        @if($paginator->onFirstPage())
            <a class="page-item previous">
                <i class="icon angle-left-icon"></i>
            </a>
        @else
            <a data-page="{{ urldecode($paginator->previousPageUrl()) }}" href="{{ urldecode($paginator->previousPageUrl()) }}" id="previous" class="page-item previous">
                <i class="icon angle-left-icon"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <a class="page-item disabled" aria-disabled="true">
                    {{ $element }}
                </a>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a class="page-item active">
                            {{ $page }}
                        </a>
                    @else
                        <a class="page-item as" href="{{ urldecode($url) }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ urldecode($paginator->nextPageUrl()) }}" data-page="{{ urldecode($paginator->nextPageUrl()) }}" id="next" class="page-item next">
                <i class="icon angle-right-icon"></i>
            </a>
        @else
            <a class="page-item next">
                <i class="icon angle-right-icon"></i>
            </a>
        @endif
    </div>
@endif
