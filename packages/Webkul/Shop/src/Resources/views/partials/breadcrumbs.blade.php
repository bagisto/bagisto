@unless ($breadcrumbs->isEmpty())
    <nav aria-label="">
        <ol class="flex">
            @foreach ($breadcrumbs as $breadcrumb)
                @if (
                    $breadcrumb->url 
                    && ! $loop->last
                )
                    <p class="flex gap-x-[10px] items-center text-[16px] font-medium">
                        <a href="{{ $breadcrumb->url }}">
                            {{ $breadcrumb->title }}
                        </a>

                        <span class="icon-arrow-right text-[24px]"></span>
                    </p>
                @else
                    <p 
                        class="flex gap-x-[10px] items-center ml-[10px] text-[#7D7D7D] text-[16px] after:content-['/'] after:last:hidden" 
                        aria-current="page"
                    >
                        {{ $breadcrumb->title }}
                    </p>
                @endif
            @endforeach
        </ol>
    </nav>
@endunless
