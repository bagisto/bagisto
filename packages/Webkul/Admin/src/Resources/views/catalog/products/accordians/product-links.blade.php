<accordian :title="'{{ __('admin::app.catalog.products.product-link') }}'" :active="true">
    <div slot="body">

        <up-selling></up-selling>

    </div>
</accordian>


@push('scripts')

<script type="text/x-template" id="up-selling-template">
    <div>
        <div class="control-group">
            <label for="up-selling">{{ __('admin::app.catalog.products.up-selling') }}</label>
            <input type="text" class="control" autocomplete="off" v-model="search_term" placeholder="{{ __('admin::app.catalog.products.product-search-hint') }}">

            <input type="hidden" name="up_sell[]" v-for='(product, index) in this.addedProduct' :value="product.id"/>

            <span class="filter-tag" style="text-transform: capitalize; margin-top: 10px; margin-right: 0px; justify-content: flex-start">
                <span class="wrapper" style="margin-left: 0px; margin-right: 10px;" v-for='(product, index) in this.addedProduct'>
                        @{{ product.name }}
                <span class="icon cross-icon" @click="removeProduct(product)"></span>
                </span>
            </span>

            <ul>
                <li v-for='(product, index) in products' style="padding: 10px; border-bottom: 1px solid #e8e8e8; width: 70%; cursor: pointer;" @click="addProduct(product)">
                    @{{ product.name }}
                </li>
            </ul>
        </div>
    </div>
</script>

<script>

        Vue.component('up-selling', {

            template: '#up-selling-template',

            data: () => ({
                allProduct: @json($allProducts),

                search_term: '',

                addedProduct: [],

                upSellingProduct: @json($product->up_sells()->get()),
            }),

            created () {
                if (this.upSellingProduct.length >= 1) {
                    for (var index in this.upSellingProduct) {
                        this.addedProduct.push(this.upSellingProduct[index]);

                        for (var index1 in this.allProduct) {
                            if (this.allProduct[index1].id == this.upSellingProduct[index].id) {
                                this.allProduct.splice(index1, 1);
                            }
                        }
                    }
                }
            },

            computed: {
                products () {
                    if (this.search_term.length >= 1) {
                        return this.allProduct.filter(product => {
                            return product.name.toLowerCase().includes(this.search_term.toLowerCase())
                        })
                    }
                }
            },

            methods: {
                addProduct(product) {
                    this.addedProduct.push(product);
                    this.search_term = '';

                    for (var index in this.allProduct) {
                        if (this.allProduct[index].id == product.id) {
                            this.allProduct.splice(index, 1);
                        }
                    }
                },

                removeProduct(product) {
                    for (var index in this.addedProduct) {
                        if (this.addedProduct[index].id == product.id ) {
                            this.allProduct.push(product);
                            this.addedProduct.splice(index, 1);
                        }
                    }
                },
            }
        });
</script>

@endpush