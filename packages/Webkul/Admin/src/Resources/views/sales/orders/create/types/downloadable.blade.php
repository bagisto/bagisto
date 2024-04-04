{!! view_render_event('bagisto.admin.sales.order.create.types.grouped.before') !!}

<v-product-downloadable-options
    :errors="errors"
    :product-options="selectedProductOptions"
></v-product-downloadable-options>

{!! view_render_event('bagisto.admin.sales.order.create.types.grouped.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-downloadable-options-template"
    >
        <x-admin::form.control-group class="p-4">
            <x-admin::form.control-group.label class="!mt-0 dark:text-white">
                @lang('admin::app.sales.orders.create.types.downloadable.title')
            </x-admin::form.control-group.label>

            <div class="grid gap-2">
                <!-- Links -->
                <div
                    class="flex gap-x-4 items-center select-none"
                    v-for="(link, index) in links"
                >
                    <x-admin::form.control-group.control
                        type="checkbox"
                        name="links[]"
                        ::for="'links[' + index + ']'"
                        ::id="'links[' + index + ']'"
                        ::value="link.id"
                        rules="required"
                        :label="trans('admin::app.sales.orders.create.types.downloadable.title')"
                    />

                    <label
                        class="text-sm text-[#6E6E6E] dark:text-gray-300 cursor-pointer"
                        :for="'links[' + index + ']'"
                    >
                        @{{ link.title + ' + ' + link.formatted_price }}
                    </label>
                </div>
            </div>

            <x-admin::form.control-group.error name="links[]" />
        </x-admin::form.control-group>
    </script>

    <script type="module">
        app.component('v-product-downloadable-options', {
            template: '#v-product-downloadable-options-template',

            props: ['errors', 'productOptions'],

            data: function() {
                return {
                    links: [],

                    isLoading: false,
                }
            },

            mounted() {
                this.getOptions();
            },

            methods: {
                getOptions() {
                    this.isLoading = true;

                    this.$axios.get("{{ route('admin.catalog.products.downloadable.options', ':replace') }}".replace(':replace', this.productOptions.product.id))
                        .then(response => {
                            this.links = response.data.data;

                            this.isLoading = false;
                        })
                        .catch(error => {});
                },
            }
        });
    </script>
@endPushOnce