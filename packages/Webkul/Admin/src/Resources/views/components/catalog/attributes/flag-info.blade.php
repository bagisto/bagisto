@props(['text' => ''])

<!--
    Small info-icon + hover tooltip used next to the attribute-configuration
    flag labels (Use In Layered, Is Configurable, Value Per Locale, Value
    Per Channel, etc.). Reuses the `icon-information` + `peer` /
    `peer-hover:block` pattern already used on the product inventory view,
    so styling stays consistent.
-->
<span class="relative inline-flex items-center">
    <span class="peer inline-flex cursor-help text-gray-400 transition-colors hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-4 w-4"
            viewBox="0 0 20 20"
            fill="currentColor"
            aria-hidden="true"
        >
            <path
                fill-rule="evenodd"
                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z"
                clip-rule="evenodd"
            />
        </svg>
    </span>

    <!--
        Tooltip sits ABOVE the icon (`bottom-full` + margin) and is anchored
        to the icon's RIGHT edge (`ltr:right-0` / `rtl:left-0`) so it extends
        toward the centre of the form rather than off the right side of the
        configuration panel. `z-50` keeps it above the accordion chrome.
    -->
    <div
        class="pointer-events-none absolute bottom-full z-50 mb-2 hidden w-64 rounded-lg bg-gray-900 p-2.5 text-xs leading-snug text-white shadow-lg peer-hover:block ltr:right-0 rtl:left-0 dark:bg-gray-700"
        role="tooltip"
    >
        {{ $text }}
    </div>
</span>
