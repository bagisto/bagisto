<products-carousel></products-carousel>

@pushOnce('scripts')
    <script type="text/x-template" id="products-carousel-template">
        <div class="container mt-20 max-lg:px-[30px] max-sm:mt-[30px]">
            <div class="flex justify-between">
                <h3 class="text-[30px] max-sm:text-[25px] font-dmserif">{{ $title }}</h3>

                <div class="flex justify-between items-center gap-8">
                    <span
                        class="icon-arrow-left-stylish text-[24px] inline-block cursor-pointer"
                    >
                    </span>

                    <span
                        class="icon-arrow-right-stylish text-[24px] inline-block cursor-pointer"
                    >
                    </span>
                </div>
            </div>

            <div class="flex gap-8 mt-[60px] overflow-auto scrollbar-hide max-sm:mt-[20px]">
                <x-shop::products.card v-for="product in products"></x-shop::products.card>
            </div>

            @if (isset($navigationLink))
                <a
                    href="{{ $navigationLink }}"
                    class="block mx-auto text-navyBlue text-base w-max font-medium py-[11px] px-[43px] border rounded-[18px] border-navyBlue bg-white mt-[60px] text-center"
                >
                    View All
                </a>
            @endif
        </div>
    </script>

    <script type="module">
        app.component('products-carousel', {
            template: '#products-carousel-template',

            data() {
                return {
                    products: @json($products)
                }
            }
        });
    </script>
@endPushOnce