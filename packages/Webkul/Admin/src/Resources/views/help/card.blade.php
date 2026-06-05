<a
    href="{{ $card['url'] }}"
    target="_blank"
    rel="noopener noreferrer"
    class="group relative flex flex-col rounded-xl border border-gray-200 bg-white p-6 transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-lg dark:border-gray-800 dark:bg-gray-900 dark:hover:border-blue-500/50"
>
    <!-- External Link Indicator -->
    <span class="absolute right-5 top-5 text-gray-300 transition-colors group-hover:text-blue-600 dark:text-gray-600 dark:group-hover:text-blue-400">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M7 17 17 7M9 7h8v8"></path>
        </svg>
    </span>

    <!-- Icon -->
    <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white dark:bg-blue-500/10 dark:text-blue-400 dark:group-hover:bg-blue-600 dark:group-hover:text-white">
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <path d="{{ $card['icon'] }}"></path>
        </svg>
    </span>

    <!-- Title -->
    <p class="mb-2 text-lg font-bold !leading-snug text-gray-800 dark:text-white">
        {{ $card['title'] }}
    </p>

    <!-- Description -->
    <p class="!leading-relaxed text-gray-600 dark:text-gray-300">
        {{ $card['info'] }}
    </p>

    <!-- Footer host -->
    <span class="mt-5 border-t border-gray-100 pt-4 text-sm font-medium text-gray-400 transition-colors group-hover:text-blue-600 dark:border-gray-800 dark:text-gray-500 dark:group-hover:text-blue-400">
        {{ $card['host'] }}
    </span>
</a>
