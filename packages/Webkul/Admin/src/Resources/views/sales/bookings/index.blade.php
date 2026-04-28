<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.sales.booking.index.title')
    </x-slot>

    <v-booking-products></v-booking-products>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-booking-products-template"
        >
            <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
                <div class="flex flex-col">
                    <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
                        @lang('admin::app.sales.booking.index.title')
                    </p>

                    <p class="-mt-1 text-sm text-gray-500 dark:text-gray-400">
                        @lang('admin::app.sales.booking.index.title') overview
                    </p>
                </div>

                <div class="flex items-center gap-2.5">
                    <!-- Export Modal -->
                    <x-admin::datagrid.export
                        v-if="viewType == 'table'"
                        src="{{ route('admin.sales.bookings.index') }}"
                    />

                    <!-- View Switcher -->
                    <div class="inline-flex rounded-lg border border-gray-200 bg-white p-1 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                        <!-- Calendar Icon -->
                        <button
                            class="icon-calendar flex h-8 w-9 items-center justify-center rounded-md text-xl transition-all duration-200"
                            :class="viewType === 'calendar'
                                ? 'bg-blue-600 text-white shadow-sm'
                                : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200'"
                            @click="viewType = 'calendar'"
                        ></button>

                        <!-- List Icon -->
                        <button
                            class="icon-list flex h-8 w-9 items-center justify-center rounded-md text-xl transition-all duration-200"
                            :class="viewType === 'table'
                                ? 'bg-blue-600 text-white shadow-sm'
                                : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200'"
                            @click="viewType = 'table'"
                        ></button>
                    </div>
                </div>
            </div>

            <template v-if="viewType == 'table'">
                <x-admin::datagrid :src="route('admin.sales.bookings.index')" />
            </template>

            <template v-else>
                @include('admin::sales.bookings.calendar')
            </template>
        </script>

        <script type="module">
            app.component('v-booking-products', {
                template: '#v-booking-products-template',

                data() {
                    return {
                        viewType: 'calendar',
                    };
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
