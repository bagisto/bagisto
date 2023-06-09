@props(['quantity' => 1])

<v-quantity-changer
    {{ $attributes }}
    :quantity="{{ $quantity }}"
></v-quantity-changer>

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

            <p v-text="qty"></p>
            
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

            props:['quantity'],

            data() {
                return  {
                    qty: this.quantity,
                }
            },

            methods: {
                increase() {
                    this.$emit('change', ++this.qty);
                },

                decrease() {
                    if (this.qty > 1) {
                        this.qty -= 1;
                    }

                    this.$emit('change', this.qty);
                },
            }
        });
    </script>
@endpushOnce
