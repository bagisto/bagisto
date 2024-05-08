@props(['item'])

<a
    href="{{ $item->url() }}"
    class="flex gap-2.5 p-1.5 items-center cursor-pointer hover:rounded-lg {{ $item->isActive() == 'active' ? 'bg-blue-600 rounded-lg' : ' hover:bg-gray-100 hover:dark:bg-gray-950' }} peer"
>
    <span class="{{ $item->getIcon() }} text-2xl {{ $item->isActive() ? 'text-white' : ''}}"></span>
        
    <p class="text-gray-600 dark:text-gray-300 font-semibold whitespace-nowrap group-[.sidebar-collapsed]/container:hidden {{ $item->isActive() ? 'text-white' : ''}}">
        {{ $item->label() }}
    </p>
</a>