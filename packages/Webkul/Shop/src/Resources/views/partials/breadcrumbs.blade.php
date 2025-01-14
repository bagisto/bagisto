@unless ($breadcrumbs->isEmpty())
    <nav aria-label="">
        <ol class="flex">
            @foreach ($breadcrumbs as $breadcrumb)
                @if (
                    $breadcrumb->url 
                    && ! $loop->last
                )
                    <li class="flex items-center gap-x-2.5 text-base font-medium">
                        <a href="{{ $breadcrumb->url }}">
                            {{ $breadcrumb->title }}
                        </a>

                        <span class="icon-arrow-right rtl:icon-arrow-left text-2xl"></span>
                    </li>
                @else
                    <li 
                        class="flex items-center gap-x-2.5 break-all text-base text-zinc-500 after:content-['/'] after:last:hidden ltr:ml-2.5 rtl:mr-0" 
                        aria-current="page"
                    >
                        {{ $breadcrumb->title }}
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endunless
