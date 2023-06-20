<v-review-summary ref="vReview"></v-review-summary>

@pushOnce('scripts')
    <script type="text/x-template" id="v-review-summary-template">
        <div v-if="isShowReviewSummary">
            <x-shop::accordion>
                <x-slot:header>
                    <div class="flex justify-between mt-2 items-center">
                        <h2 class="text-[26px] font-medium">@lang('Order Summary')</h2>
                    </div>
                </x-slot:header>

                <x-slot:content>
                    <div class="flex justify-between">
                        <div>
                            <div>
                                <b>@lang('Billing Address')</b>
                            </div>

                            <ul type="none">
                                <li>@{{ reviewCart.billing_address.company_name }}</li>
                                <li>@{{ reviewCart.billing_address.first_name }} @{{ reviewCart.billing_address.last_name }}</li>
                                <li>@{{ reviewCart.billing_address.address1 }}</li>
                                <li>@{{ reviewCart.billing_address.postcode }} @{{ reviewCart.billing_address.city }}</li>
                                <li>@{{ reviewCart.billing_address.state }}</li>
                                <li>@{{ reviewCart.billing_address.country }} @{{ reviewCart.billing_address.postcode }}</li>
                                <li><span class="font-medium">@lang('Contact'):</span> @{{ reviewCart.billing_address.phone }}</li>
                            </ul>
                        </div>

                        <div v-if="
                                reviewCart.haveStockableItems 
                                && reviewCart.shipping_address
                            "
                        >
                            <div>
                                <b>@lang('Shipping Address')</b>
                            </div>

                            <ul type="none">
                                <li>@{{ reviewCart.shipping_address.company_name }}</li>
                                <li>@{{ reviewCart.shipping_address.first_name }} @{{ reviewCart.shipping_address.last_name }}</li>
                                <li>@{{ reviewCart.shipping_address.address1 }}</li>
                                <li>@{{ reviewCart.shipping_address.postcode }} @{{ reviewCart.shipping_address.city }}</li>
                                <li>@{{ reviewCart.shipping_address.state }}</li>
                                <li>@{{ reviewCart.shipping_address.country }} @{{ reviewCart.shipping_address.postcode }}</li>
                                <li><span class="font-medium">@lang('Contact'):</span> @{{ reviewCart.shipping_address.phone }}</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div v-for="item in reviewCart.items" :key="item">
                        <div class="grid border-b-[1px] border-[#E9E9E9] mt-[40px]">
                            <div class="flex gap-x-[15px] pb-[20px]">
                                <img
                                    class="max-w-[90px] max-h-[90px] w-[90px] h-[90px] rounded-md"
                                    :src="item.image.medium_image_url"
                                    :title="item.name"
                                    :alt="item.name"
                                />
                                
                                <div>
                                    {{-- Need to discussed with (@devansh-sir) about these events --}}
                                    {{-- {!! view_render_event('bagisto.shop.checkout.name.before', ['item' => $item]) !!} --}}

                                    <p class="text-[26px] font-medium text-navyBlue">@{{ item.name }}</p>

                                    {{-- {!! view_render_event('bagisto.shop.checkout.name.after', ['item' => $item]) !!}
                                    {!! view_render_event('bagisto.shop.checkout.price.before', ['item' => $item]) !!} --}}

                                    <p class="text-[18px] font-medium mt-[10px]">@{{ item.formatted_total }}</p>

                                    {{-- {!! view_render_event('bagisto.shop.checkout.price.after', ['item' => $item]) !!}
                                    {!! view_render_event('bagisto.shop.checkout.quantity.before', ['item' => $item]) !!} --}}

                                    <p class="text-[18px]">@{{ item.formatted_price }} X @{{ item.quantity }} (@lang('Quantity'))</p>

                                    {{-- {!! view_render_event('bagisto.shop.checkout.quantity.after', ['item' => $item]) !!}

                                    {!! view_render_event('bagisto.shop.checkout.options.before', ['item' => $item]) !!} --}}
                                </div>
                            </div>
                            {{-- Need to show additionl data of product --}}
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <div>
                            <div class="grid gap-[15px] mt-[25px] mb-[30px]">
                                <div v-if="reviewCart.haveStockableItems">
                                    <p class="text-[16px] font-medium">@{{ reviewCart.selected_shipping_rate }}</p>
                                    <p class="text-[16px]">@{{ reviewCart.selected_shipping_rate_method }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-[16px]">@lang('Payment method')</p>
                                    <p class="text-[16px] font-medium">@{{ reviewCart.payment_method }}</p>
                                </div>

                                <div
                                    class="block bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
                                    @click="placeOrder"
                                >
                                    @lang('Place Order')
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="grid gap-[15px] mt-[25px] mb-[30px]">
                                <div class="flex text-right justify-between">
                                    <p class="text-[16px]">@lang('Subtotal')</p>
                                    <p class="text-[16px] font-medium">@{{ reviewCart.base_sub_total  }}</p>
                                </div>

                                <div 
                                    class="flex text-right justify-between"
                                    v-for="(amount, index) in reviewCart.base_tax_amounts"
                                    v-if="parseFloat(reviewCart.base_tax_total)"
                                >
                                    <p class="text-[16px]">Tax (@{{ index }})%</p>
                                    <p class="text-[16px] font-medium">@{{ amount }}</p>
                                </div>

                                <div 
                                    class="flex text-right justify-between"
                                    v-if="reviewCart.selected_shipping_rate"
                                >
                                    <p class="text-[16px] mr-2">@lang('Delivery Charges')</p>
                                    <p class="text-[16px] font-medium">@{{ reviewCart.selected_shipping_rate }}</p>
                                </div>

                                <div 
                                    class="flex text-right justify-between"
                                    v-if="reviewCart.base_discount_amount && parseFloat(reviewCart.base_discount_amount) > 0"
                                >
                                    <p class="text-[16px] mr-2">@lang('Discount amount')</p>
                                    <p class="text-[16px] font-medium">@{{ reviewCart.formatted_base_discount_amount }}</p>
                                </div>

                                <div class="flex text-right justify-between">
                                    <p class="text-[16px] mr-2">@lang('Grand total')</p>
                                    <p class="text-[16px] font-medium"> @{{ reviewCart.base_grand_total }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot:content>
            </x-shop::accordion>
        </div>
    </script>

    <script type="module">
        app.component('v-review-summary', {
            template: '#v-review-summary-template',

            data() {
                return  {
                    isShowReviewSummary: false,

                    reviewCart: {},
                }
            }, 

            methods: {
                placeOrder() {
                    this.$axios.post("{{ route('shop.checkout.save_order') }}", {
                            '_token': "{{ csrf_token() }}"
                        })
                        .then(response => {
                            if (response.data.success) {
                                if (response.data.redirect_url) {
                                    window.location.href = response.data.redirect_url;
                                } else {
                                    window.location.href = "{{ route('shop.checkout.success') }}";
                                }
                            }
                        })
                        .catch(error => console.log(error))
                }
            }
        });
    </script>
@endPushOnce