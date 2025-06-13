@props([
    'meta' => [],
    'onPageChangeMethodName' => '' // This will be a string, e.g., 'handlePageChange'
])

@if (!empty($meta) && isset($meta['last_page']) && $meta['last_page'] > 1)
    <nav
        role="navigation"
        aria-label="@lang('shop::app.components.pagination.pagination-aria-label')"
        class="mt-12 flex flex-col items-center justify-between gap-y-4 sm:flex-row"
    >
        <!-- Results Info -->
        <div class="font-lato text-sm text-zylver-olive-green/80">
            @lang('shop::app.components.pagination.showing')
            <span class="font-semibold text-zylver-olive-green">@{{ meta.from }}</span>
            @lang('shop::app.components.pagination.to')
            <span class="font-semibold text-zylver-olive-green">@{{ meta.to }}</span>
            @lang('shop::app.components.pagination.of')
            <span class="font-semibold text-zylver-olive-green">@{{ meta.total }}</span>
            @lang('shop::app.components.pagination.results')
        </div>

        <div class="flex items-center gap-x-1 font-lato text-sm">
            <!-- Previous Button -->
            <button
                type="button"
                class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-transparent p-2 text-zylver-olive-green/80 transition-colors hover:border-zylver-border-grey hover:bg-zylver-border-grey/30 disabled:cursor-not-allowed disabled:opacity-50"
                :disabled="meta.current_page <= 1"
                @click="{{ $onPageChangeMethodName ? $onPageChangeMethodName . '(' . (isset($meta['current_page']) ? $meta['current_page'] - 1 : 1) . ')' : '' }}"
                aria-label="@lang('shop::app.components.pagination.previous')"
            >
                <span class="icon-arrow-left text-2xl"></span>
            </button>

            @php
                $currentPage = isset($meta['current_page']) ? (int)$meta['current_page'] : 1;
                $lastPage = isset($meta['last_page']) ? (int)$meta['last_page'] : 1;
                $onPageClick = $onPageChangeMethodName ?? '';

                $pages = [];
                $range = 1; // Number of pages to show before and after current page
                
                if ($lastPage <= (2 * $range + 3)) { // Show all pages if total is small (e.g., <=5 pages)
                    for ($i = 1; $i <= $lastPage; $i++) {
                        $pages[] = ['number' => $i, 'active' => $i == $currentPage];
                    }
                } else {
                    // Always show first page
                    $pages[] = ['number' => 1, 'active' => 1 == $currentPage];

                    // Ellipsis after first page?
                    if ($currentPage > $range + 2) { // e.g. current page is 4 or more, range 1 (1 ... 3 4 5)
                        $pages[] = ['number' => '...', 'active' => false];
                    }

                    // Pages around current page
                    $startRange = max(2, $currentPage - $range);
                    $endRange = min($lastPage - 1, $currentPage + $range);

                    for ($i = $startRange; $i <= $endRange; $i++) {
                        $pages[] = ['number' => $i, 'active' => $i == $currentPage];
                    }
                    
                    // Ellipsis before last page?
                    if ($currentPage < $lastPage - $range - 1) { // e.g. current page is 3, last page 7, range 1 (1 2 3 ... 7)
                        $pages[] = ['number' => '...', 'active' => false];
                    }
                    
                    // Always show last page
                    $pages[] = ['number' => $lastPage, 'active' => $lastPage == $currentPage];

                    // Filter out duplicates and ensure correct order if ranges overlap
                    $uniquePages = [];
                    $numbersSeen = [];
                    foreach ($pages as $p) {
                        if (!in_array($p['number'], $numbersSeen) || $p['number'] === '...') {
                            // Allow multiple ellipses if logic dictates, but not consecutive identical numbers
                            if ($p['number'] === '...' && !empty($uniquePages) && end($uniquePages)['number'] === '...') {
                                // skip consecutive ellipsis
                            } else {
                                $uniquePages[] = $p;
                            }
                            if ($p['number'] !== '...') {
                                $numbersSeen[] = $p['number'];
                            }
                        }
                    }
                    $pages = $uniquePages;
                }
            @endphp

            @foreach ($pages as $page)
                @if ($page['number'] === '...')
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-transparent p-2 text-zylver-olive-green/60">...</span>
                @else
                    <button
                        type="button"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-md border p-2 transition-colors"
                        :class="{
                            'border-zylver-gold bg-zylver-gold text-zylver-white hover:bg-zylver-gold/90': {{ $page['active'] ? 'true' : 'false' }},
                            'border-transparent text-zylver-olive-green/80 hover:border-zylver-border-grey hover:bg-zylver-border-grey/30': {{ !$page['active'] ? 'true' : 'false' }}
                        }"
                        @click="{{ $onPageClick && is_numeric($page['number']) ? $onPageClick . '(' . $page['number'] . ')' : '' }}"
                        aria-current="{{ $page['active'] ? 'page' : 'false' }}"
                        :disabled="'{{ $page['number'] === '...' }}'"
                    >
                        {{ $page['number'] }}
                    </button>
                @endif
            @endforeach

            <!-- Next Button -->
            <button
                type="button"
                class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-transparent p-2 text-zylver-olive-green/80 transition-colors hover:border-zylver-border-grey hover:bg-zylver-border-grey/30 disabled:cursor-not-allowed disabled:opacity-50"
                :disabled="!meta.current_page || meta.current_page >= meta.last_page"
                @click="{{ $onPageChangeMethodName ? $onPageChangeMethodName . '(' . (isset($meta['current_page']) ? $meta['current_page'] + 1 : 1) . ')' : '' }}"
                aria-label="@lang('shop::app.components.pagination.next')"
            >
                <span class="icon-arrow-right text-2xl"></span>
            </button>
        </div>
    </nav>
@endif
