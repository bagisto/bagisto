{!! view_render_event('bagisto.admin.sales.order.create.types.grouped.before') !!}

<v-product-grouped-options
    :errors="errors"
    :product-options="selectedProductOptions"
></v-product-grouped-options>

{!! view_render_event('bagisto.admin.sales.order.create.types.grouped.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-grouped-options-template"
    >
        <div class="grid gap-5 p-4">
            <div
                class="flex gap-2 justify-between items-center"
                v-for="product in associatedProducts"
            >
                <div class="grid gap-1.5">
                    <p class="text-sm font-medium dark:text-white">
                        @lang('admin::app.sales.orders.create.types.grouped.name')
                    </p>

                    <p class="text-sm text-[#6E6E6E] dark:text-gray-300">
                        @{{ product.name + ' + ' + product.formatted_price }}
                    </p>
                </div>

                <x-admin::quantity-changer
                    ::name="'qty[' + product.id + ']'"
                    ::value="product.qty"
                    class="gap-x-4 w-max rounded-l py-1 px-4"
                    @change="updateItem($event)"
                />
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-product-grouped-options', {
            template: '#v-product-grouped-options-template',

            props: ['errors', 'productOptions'],

            data: function() {
                return {
                    associatedProducts: [],

                    isLoading: false,
                }
            },

            mounted() {
                this.getOptions();
            },

            methods: {
                getOptions() {
                    this.isLoading = true;

                    this.$axios.get("{{ route('admin.catalog.products.grouped.options', ':replace') }}".replace(':replace', this.productOptions.product.id))
                        .then(response => {
                            this.associatedProducts = response.data.data;

                            this.isLoading = false;
                        })
                        .catch(error => {});
                },
            }
        });
    </script>
@endPushOnce