@unless ($breadcrumbs->isEmpty())
    <nav aria-label="">
        <ol class="flex">
            @foreach ($breadcrumbs as $breadcrumb)
                @if (
                    $breadcrumb->url 
                    && ! $loop->last
                )
                    <li class="flex gap-x-2.5 items-center text-base font-medium">
                        <a href="{{ $breadcrumb->url }}">
                            {{ $breadcrumb->title }}
                        </a>

                        <span class="icon-arrow-right text-2xl"></span>
                    </li>
                @else
                    <li 
                        class="flex gap-x-2.5 items-center ml-2.5 text-[#6E6E6E] text-base after:content-['/'] after:last:hidden" 
                        aria-current="page"
                    >
                        {{ $breadcrumb->title }}
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endunless
