<div class="tabs">
    @foreach ($menu as $menuItem)
        @if (
            $menuItem->isActive()
            && $tabs = $menuItem->getCurrentActiveItem()->menuItems ?? false
        )
            <div class="mb-4 flex gap-4 border-b-2 pt-2 dark:border-gray-800 max-sm:hidden">
                @foreach ($tabs as $key => $tab)
                    <a href="{{ $tab->url() }}">
                        <div class="{{  $tab->isActive() ? "-mb-px border-blue-600 border-b-2 transition" : '' }} pb-3.5 px-2.5 text-base  font-medium text-gray-600 dark:text-gray-300 cursor-pointer">
                            @lang($tab->name)
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    @endforeach
</div>