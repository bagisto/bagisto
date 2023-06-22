<x-shop::layouts>
    <!-- Breadcrumb -->
    <div class="flex justify-center mt-[20px] max-lg:hidden">
		<div class="flex gap-x-[10px] items-center">
			<p class="flex items-center gap-x-[10px] text-[12px] font-medium">
                Home
                <span class="icon-arrow-right text-[22px]"></span>
			</p>

			<p class="flex items-center gap-x-[16px] text-[12px] font-medium">
                Product Compare
                <span class="icon-arrow-right text-[22px] last:hidden"></span>
            </p>
		</div>
	</div>

    {{-- Compare Component --}}
    <div class="container px-[60px] max-lg:px-[30px] max-sm:px-[15px] mt-[30px]">
        <v-compare>
            <x-shop::shimmer.compare></x-shop::shimmer.compare>
        </v-compare>
    </div>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-compare-template">
            <div v-if="! isLoading">
                <div class="flex justify-between items-center">
                    <h2 class="text-[26px] font-medium">Product Compare</h2>

                    <div class="flex items-center gap-x-[10px] border border-[#E9E9E9] rounded-[12px] py-[12px] px-[20px] cursor-pointer">
                        <span class="icon-bin text-[24px]"></span>
                        Delete All
                    </div>
                </div>

                <div class="grid mt-[60px] overflow-auto journal-scroll">
                    <template v-for="attribute in comparableAttributes">
                        <!---- Product Card -->
                        <div
                            class="flex items-center max-w-full border-b-[1px] border-[#E9E9E9]"
                            v-if="attribute.code == 'product'"
                        >
                            <div class="min-w-[304px] max-w-full">
                                <p class="text-[14px] font-medium">@{{ attribute.name ?? attribute.admin_name }}</p>
                            </div>

                            <div class="flex gap-[12px] border-l-[1px] border-[#E9E9E9]">
                                <x-shop::products.card
                                    v-for="product in items"
                                    class="min-w-[311px] max-w-[311px] pt-0 pr-0 p-[20px]"
                                ></x-shop::products.card>
                            </div>
                        </div>

                        <!---- Comparable Attributes -->
                        <div class="flex items-center max-w-full border-b-[1px] border-[#E9E9E9]">
                            <div class="min-w-[304px] max-w-full">
                                <p class="text-[14px] font-medium">
                                    @{{ attribute.name ?? attribute.admin_name }}
                                </p>
                            </div>

                            <div
                                class="w-[311px] max-w-[311px]  pr-0 p-[20px]"
                                v-for="(product, index) in items"
                            >
                                <p class="text-[14px]">
                                    @{{ product[attribute.code] ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else>
                <x-shop::shimmer.compare></x-shop::shimmer.compare>
            </div>
        </script>

        <script type="module">
            app.component("v-compare", {
                template: '#v-compare-template',

                data() {
                    return  {
                        comparableAttributes: [
                            ...[{'code': 'product', 'name': 'Product'}],
                            ...@json($comparableAttributes)
                        ],

                        items: [],

                        isCustomer: '{{ auth()->guard('customer')->check() }}',

                        isLoading: true,
                    }
                },

                mounted() {
                    this.getItems();
                },

                methods: {
                    getItems() {
                        let productIds = [];
                        
                        if (! this.isCustomer) {
                            productIds = this.getStorageValue('compare_items');
                        }

                        this.$axios.get("{{ route('shop.api.compare.index') }}", {
                                params: {
                                    product_ids: productIds,
                                },
                            })
                            .then(response => {
                                this.isLoading = false;

                                this.items = response.data.data;
                            })
                            .catch(error => {});
                    },

                    remove(productId) {
                        if (! this.isCustomer) {
                            let items = this.items
                                .filter(function (item) {
                                    if (item.id == productId) {

                                        return false;
                                    }

                                    return true;
                                });

                            //let items = this.getStorageValue()
                                //.filter(item => item != productId);

                            //window.localStorage.setItem('compare_items', JSON.stringify(items));

                            //this.getItems();

                            return;
                        }

                        this.$axios.post("{{ route('shop.api.compare.destroy') }}", {
                                '_method': 'DELETE',
                                'product_id': productId,
                            })
                            .then(response => {
                                this.items = response.data.data;
                            })
                            .catch(error => {});
                    },

                    getStorageValue() {
                        let value = window.localStorage.getItem('compare_items');

                        if (! value) {
                            return [];
                        }

                        return JSON.parse(value);
                    },
                }
            });
        </script>
    @endpushOnce
</x-shop::layouts>
