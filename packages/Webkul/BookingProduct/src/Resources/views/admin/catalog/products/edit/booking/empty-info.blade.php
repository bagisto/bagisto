@pushOnce('scripts')
    <script type="text/x-template" id="v-empty-info-template">
        <div class="grid gap-3 justify-items-center">
            <!-- Attribute Option Image -->
            <img
                class="w-[120px] h-[120px] rounded"
                src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                alt="@lang('admin::app.catalog.attributes.create.add-attribute-options')"
            />

            <!-- Add Slots Information -->
            <div class="flex flex-col gap-1.5 items-center">
                <p
                    class="text-base text-gray-400 font-semibold"
                    v-if="type == 'event'"
                >
                    @lang('booking::app.admin.catalog.products.edit.type.booking.tickets.add')
                </p>

                <p
                    class="text-base text-gray-400 font-semibold"
                    v-else
                >
                    @lang('booking::app.admin.catalog.products.edit.type.booking.slots.add')
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