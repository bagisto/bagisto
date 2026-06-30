<!-- Compare Count Vue Component -->
<v-compare-count></v-compare-count>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-compare-count-template"
    >
        <span
            class="absolute -top-4 rounded-[44px] bg-navyBlue px-2 py-1.5 text-xs font-semibold leading-[9px] text-white ltr:left-5 rtl:right-5 max-md:px-2 max-md:py-1.5 max-md:ltr:left-4 max-md:rtl:right-4"
            v-if="count"
        >
            @{{ count }}
        </span>
    </script>

    <script type="module">
        app.component('v-compare-count', {
            template: '#v-compare-count-template',

            data() {
                return {
                    count: 0,

                    isCustomer: '{{ auth()->guard('customer')->check() }}',
                };
            },

            mounted() {
                this.getCount();

                this.$emitter.on('update-compare-count', (count) => {
                    if (typeof count === 'number') {
                        this.count = count;

                        return;
                    }

                    this.getCount();
                });
            },

            methods: {
                getCount() {
                    let productIds = [];

                    if (! this.isCustomer) {
                        productIds = this.getStorageValue();
                    }

                    this.$axios.get('{{ route("shop.api.compare.index") }}', {
                            params: {
                                product_ids: productIds,
                            },
                        })
                        .then(response => {
                            this.count = response.data.data.length;
                        })
                        .catch(error => {});
                },

                getStorageValue() {
                    let value = localStorage.getItem('compare_items');

                    if (! value) {
                        return [];
                    }

                    return JSON.parse(value);
                },
            },
        });
    </script>
@endpushOnce
