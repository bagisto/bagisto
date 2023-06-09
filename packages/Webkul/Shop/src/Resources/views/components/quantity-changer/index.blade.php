@props([
    'cartItem' => null,
])

<v-quantity-changer 
    {{ $attributes }}
    :cartItem="{{ $cartItem }}" 
    @updateItem='updateItem'
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

            props:['cartItem'],

            data() {
                return  {
                    quantity: this.cartItem.quantity ?? 1,
                }
            },

            methods: {
                increase() {
                    this.quantity += 1;

                    this.$emit('change', this.quantity);

                    /**
                     * Update quantity to the database.
                     */
                    this.$emit('updateItem', {
                        id: this.cartItem.id,
                        quantity: this.quantity
                    });
                },

                decrease() {
                    if (this.quantity > 1) this.quantity -= 1;

                    this.$emit('change', this.quantity);

                    /**
                     * Update quantity to the database.
                     */
                    this.$emit('updateItem', {
                        id: this.cartItem.id,
                        quantity: this.quantity
                    });
                },
            }
        });
    </script>
@endpushOnce
