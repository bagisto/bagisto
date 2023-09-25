@unless ($breadcrumbs->isEmpty())
    <nav aria-label="">
        <ol class="flex">
            @foreach ($breadcrumbs as $breadcrumb)
                @if (
                    $breadcrumb->url 
                    && ! $loop->last
                )
                    <li class="flex gap-x-[10px] items-center text-[16px] font-medium">
                        <a href="{{ $breadcrumb->url }}">
                            {{ $breadcrumb->title }}
                        </a>

                        <span class="icon-arrow-right text-[24px]"></span>
                    </li>
                @else
                    <li 
                        class="flex gap-x-[10px] items-center ml-[10px] text-[#6E6E6E] text-[16px] after:content-['/'] after:last:hidden" 
                        aria-current="page"
                    >
                        {{ $breadcrumb->title }}
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endunless
