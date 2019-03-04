<accordian :title="'{{ __('admin::app.catalog.products.product-link') }}'" :active="true">
    <div slot="body">

        <up-selling></up-selling>

    </div>
</accordian>


@push('scripts')

<script type="text/x-template" id="up-selling-template">
    <div>

        <div class="control-group">
            <input type="text" class="control" autocomplete="off" v-model="search_term">

            <span class="filter-tag" style="text-transform: capitalize; margin-top: 10px; margin-right: 0px; justify-content: flex-start">
                <span class="wrapper" style="margin-left: 0px; margin-right: 10px;" v-for='(product, index) in this.product_name'>
                        @{{ product.name }}
                <span class="icon cross-icon" @click="removeProduct(product.name)"></span>
                </span>
            </span>

            <ul>
                <li v-for='(product, index) in products' style="padding: 10px; border-bottom: 1px solid; width: 70%; cursor: pointer;" @click="addProduct(product.name)">
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
                allProduct : @json($allProducts),

                search_term: '',

                product_name: [],
            }),

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
                addProduct(productName) {
                    this.product_name.push({'name': productName});
                    this.search_term = '';
                },

                removeProduct(productName) {
                    for (var index in  this.product_name) {
                        if ( this.product_name[index].name == productName ) {
                            let data = Array.prototype.slice.call(this.product_name, index);

                            console.log(data);
                        }
                    }
                },
            }
        });
</script>
@endpush