@if ($paginator->hasPages()) 
    <div class="flex gap-x-2 items-center p-4 border-t dark:border-gray-800">
        <p
            class="inline-flex gap-x-1 items-center justify-between ltr:ml-2 rtl:mr-2 text-gray-600 dark:text-gray-300 py-1.5 px-2 leading-6 text-center w-full max-w-max bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-md marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black max-sm:hidden"
        >
            {{ $paginator->perPage() }}
        </p>

        <span class="text-gray-600 dark:text-gray-300 whitespace-nowrap">
            @lang('admin::app.customers.customers.view.per-page')
        </span>

        <p
            class="inline-flex gap-x-1 items-center justify-between ltr:ml-2 rtl:mr-2 text-gray-600 dark:text-gray-300 py-1.5 px-2 leading-6 text-center w-full max-w-max bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-md marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black max-sm:hidden"
        >
            {{ $paginator->currentPage() }}
        </p>

        <span class="text-gray-600 dark:text-gray-300 whitespace-nowrap">
            @lang('admin::app.customers.customers.view.of')
        </span>

        <p
            class="inline-flex gap-x-1 items-center justify-between ltr:ml-2 rtl:mr-2 text-gray-600 dark:text-gray-300 py-1.5 px-2 leading-6 text-center w-full max-w-max bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-md marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black max-sm:hidden"
        >
            {{ $paginator->lastPage() }}
        </p>

        <div class="flex gap-1 items-center">
            <a href="{{ $paginator->previousPageUrl() }}">
                <div class="inline-flex gap-x-1 items-center justify-between ltr:ml-2 rtl:mr-2 text-gray-600 dark:text-gray-300 p-1.5 text-center w-full max-w-max bg-white dark:bg-gray-900 border rounded-md dark:border-gray-800 cursor-pointer transition-all hover:border hover:bg-gray-100 dark:hover:bg-gray-950 marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black">
                    <span class="icon-sort-left text-2xl"></span>
                </div>
            </a>

            <a href="{{ urldecode($paginator->nextPageUrl()) }}">
                <div class="inline-flex gap-x-1 items-center justify-between ltr:ml-2 rtl:mr-2 text-gray-600 dark:text-gray-300 p-1.5 text-center w-full max-w-max bg-white dark:bg-gray-900 border rounded-md dark:border-gray-800 cursor-pointer transition-all hover:border hover:bg-gray-100 dark:hover:bg-gray-950 marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black">
                    <span class="icon-sort-right text-2xl"></span>
                </div>
            </a>
        </div>
    </div>
@endif
