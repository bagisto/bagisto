@props(['defaultQuantity' => 1])

<v-quantity-changer
    {{ $attributes }}
    :default-quantity="{{ $defaultQuantity }}"
>
</v-quantity-changer>

@pushOnce('scripts')
    <script type="text/x-template" id="v-quantity-changer-template">
        <div 
            {{ $attributes->merge(['class' => 'flex border border-navyBlue items-center']) }}
        >
            <span 
                class="bg-[position:-172px_-44px] bs-main-sprite w-[14px] h-[14px] cursor-pointer" 
                @click="decrease"
            >
            </span>

            <p v-text="quantity"></p>
            
            <span 
                class="bg-[position:-5px_-69px] bs-main-sprite w-[14px] h-[14px] cursor-pointer"
                @click="increase"
            >
            </span>
        </div>
    </script>

    <script type="module">
        app.component("v-quantity-changer", {
            template: '#v-quantity-changer-template',

            props:['defaultQuantity'],

            data() {
                return  {
                    quantity: this.defaultQuantity,
                }
            },

            methods: {
                increase() {
                    this.$emit('change', ++this.quantity);
                },

                decrease() {
                    if (this.quantity > 1) {
                        this.quantity -= 1;
                    }

                    this.$emit('change', this.quantity);
                },
            }
        });
    </script>
@endpushOnce
