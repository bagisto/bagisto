@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-empty-info-template"
    >
        <div class="grid gap-3.5 justify-center justify-items-center py-10 px-2.5">
            <img
                src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                class="w-20 h-20 dark:invert dark:mix-blend-exclusion"
            >

            <div class="flex flex-col gap-2 items-center">
                <p
                    class="text-base text-gray-400 font-semibold"
                    v-if="type == 'event'"
                >
                    @lang('booking::app.admin.catalog.products.edit.booking.empty-info.tickets.add')
                </p>

                <p
                    class="text-base text-gray-400 font-semibold"
                    v-else
                >
                    @lang('booking::app.admin.catalog.products.edit.booking.empty-info.slots.add')
                </p>

                <p class="text-gray-400">
                    @lang('booking::app.admin.catalog.products.edit.booking.empty-info.slots.description')
                </p>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-empty-info', {
            template: '#v-empty-info-template',

            props: ['type'],
        });
    </script>
@endpushOnce