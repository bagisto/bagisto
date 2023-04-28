<products-carousel>

    @foreach ($products as $product)
        <x-products.card :product="$product"></x-products.card>
    @endforeach

</products-carousel>


@pushOnce('scripts')
    <script type="text/x-template" id="products-carousel-template">
        <div class="container mt-20 max-lg:px-[30px] max-sm:mt-[30px]">
            <div class="flex justify-between">
                <h3 class="text-[30px] max-sm:text-[25px] font-dmserif">{{ $title }}</h3>
                <div class="flex justify-between items-center gap-8">
                    <span
                        class="bg-[position:-122px_-137px] bs-main-sprite w-[21px] h-[20px] inline-block cursor-pointer"></span>
                    <span
                        class="bg-[position:-147px_-137px] bs-main-sprite w-[21px] h-[20px] inline-block cursor-pointer"></span>
                </div>
            </div>

            <div class="flex gap-8 mt-[60px] overflow-auto scrollbar-hide max-sm:mt-[20px]">

                <slot></slot>

            </div>

            @if (isset($navigationLink))
                <a
                href="{{ $navigationLink } }}"
                class="block mx-auto text-navyBlue text-base w-max font-medium py-[11px] px-[43px] border rounded-[18px] border-navyBlue bg-white mt-[60px] text-center">View
                All</a>
            @endif
        </div>
    </script>

    <script>
        Vue.component('products-carousel', {
            template: '#products-carousel-template',

            data() {
                return {

                }
            },

            created() {

            },

            methods: {
            }
        });
    </script>
@endPushOnce