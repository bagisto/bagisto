<div class="tabs">
    @if ($tabs = menu()->getCurrentActiveMenu()->getChildren())
        @if ($tabs->isNotEmpty())
            <div class="mb-4 flex gap-4 border-b-2 pt-2 dark:border-gray-800 max-sm:hidden">
                @foreach ($tabs as $key => $tab)
                    <a href="{{ $tab->getUrl() }}">
                        <div class="{{  $tab->isActive() ? "-mb-px border-blue-600 border-b-2 transition" : '' }} pb-3.5 px-2.5 text-base  font-medium text-gray-600 dark:text-gray-300 cursor-pointer">
                            {{ $tab->getName() }}
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    @endif
</div>
