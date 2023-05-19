<product-card {{ $attributes }} :product="product"></product-card>

@pushOnce('scripts')
    <script type="text/x-template" id="product-card-template">
        <div class="bs-single-card relative min-w-[291px]">
            <div class="">
                <img class="" src="{{ bagisto_asset('/images/mens-collections.jpg') }}">
            </div>

            <div class="action-items">
                <p
                    class="rounded-[44px] text-[#fff] text-[14px] px-[10px] bg-navyBlue inline-block absolute top-[20px] left-[20px]">
                    New</p>
                <span
                    class=" flex justify-center items-center w-[30px] h-[30px] bg-white rounded-md cursor-pointer absolute top-[20px] right-[20px] before:content-[' '] before:bg-[position:-170px_-65px] before:bs-main-sprite before:w-[21px] before:h-[20px] before:block"></span>

                    <compare-component product-id="1"></compare-component>

                    @php
                        $product = [
                            'type' => 'simple',
                            'isSaleable' => true,
                        ];    
                    @endphp

                {!! view_render_event('bagisto.shop.products.add_to_cart.before', ['product' => $product]) !!}

                    <button
                        type="submit"
                        class="rounded-xl bg-white text-navyBlue text-xs w-max font-medium py-[11px] px-[43px]  absolute top-[244px] left-[50%] -translate-x-[50%]"
                        {{ ! $product["isSaleable"] ? 'disabled' : '' }}
                    >
                        {{ $product['type'] == 'booking' ? __('shop::app.products.book-now') : __('shop::app.products.add-to-cart') }}
                    </button>

                {!! view_render_event('bagisto.shop.products.add_to_cart.after', ['product' => $product]) !!}

            </div>

            <p class="text-base">@{{ product.name }}</p>
            
            <div class="price-block">
                <p class="offer-price">$20.00</p>
                <p class="original-price">$30.00</p>
            </div>

            <div class="change-card-color">
                <span class="bg-[#B5DCB4] active"></span>
                <span class="bg-[#5C5C5C]"></span>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('product-card', {
            template: '#product-card-template',

            props: ['product'],
        });
    </script>

    <script type="text/x-template" id="compare-component-template">
        <a
            class="flex justify-center items-center w-[30px] h-[30px] bg-white rounded-md cursor-pointer absolute top-[60px] right-[20px] before:content-[' '] before:bg-[position:-98px_-90px] before:bs-main-sprite before:w-[21px] before:h-[20px] before:block"
            title="{{  __('shop::app.customer.compare.add-tooltip') }}"
        >
        </a>
    </script>

    <script type="module">
        app.component('compare-component', {
            props: ['productId'],

            template: '#compare-component-template',

            data: function () {
                return {
                    'baseUrl': "{{ url()->to('/') }}",
                    'customer': '{{ auth()->guard('customer')->user() ? "true" : "false" }}' == "true",
                }
            },

            created() {
                console.log(this.product);
            }
        });
    </script>
@endpushOnce