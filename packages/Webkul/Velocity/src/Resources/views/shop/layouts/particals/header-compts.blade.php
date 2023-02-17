
<header-component
    items-count-src="{{ route('velocity.product.item-count') }}"
></header-component>

@push('scripts')
    <script type="text/x-template" id='header-component-template'>
        <div>
            <wishlist-component-with-badge
                is-customer="{{ auth()->guard('customer')->check() ? 'true' : 'false' }}"
                is-text="{{ isset($isText) && $isText ? 'true' : 'false' }}"
                src="{{ route('shop.customer.wishlist.index') }}"
                :wishlist-item-count='wishlistCount'>
            </wishlist-component-with-badge>

            <compare-component-with-badge
                is-customer="{{ auth()->guard('customer')->check() ? 'true' : 'false' }}"
                is-text="{{ isset($isText) && $isText ? 'true' : 'false' }}"
                src="{{ auth()->guard('customer')->check() ? route('velocity.customer.product.compare') : route('velocity.product.compare') }}"
                :compare-item-count='compareCount'>
            </compare-component-with-badge>
        </div>
    </script>

    <script>
        Vue.component('header-component',{
            template: '#header-component-template',

            props: ['itemsCountSrc'],

            data: function() {
                return {
                    isCustomer: "{{ auth()->guard('customer')->check() ? true : false }}",
                    compareCount: 0,
                    wishlistCount: 0,
                }
            },

            watch: {
                '$root.headerItemsCount': function () {
                    this.updateHeaderItemsCount();
                },
            },

            mounted () {
                this.updateHeaderItemsCount();
            },

            methods: {
                updateHeaderItemsCount: async function () {
                    if (this.isCustomer != true) {
                        let comparedItems = this.getStorageValue('compared_product');

                        if (comparedItems) {
                            this.compareCount = comparedItems.length;
                        }
                    } else {
                        const response = await fetch(this.itemsCountSrc);
                        const data = await response.json();

                        this.compareCount = data.compareProductsCount;
                        this.wishlistCount = data.wishlistedProductsCount;
                    }
                },
            },
        })
    </script>
@endpush