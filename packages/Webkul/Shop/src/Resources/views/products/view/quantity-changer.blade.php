
<v-quantity-changer @updateQuantity='updateQty($event)'></v-quantity-changer>

@pushOnce('scripts')
    <script type="text/x-template" id="v-quantity-changer-template">
        <div class="flex gap-x-[25px] border rounded-[12px] border-navyBlue py-[15px] px-[26px] items-center">
        
            <span 
                class="icon-plus text-[24px] cursor-pointer"
                @click='increase()'
            >
            </span>
            <p>@{{ quantity }}</p>
            <span 
                class="icon-minus font-bold text-[24px cursor-pointer"
                @click='decrease()'
            >
            </span>
        </div>
    </script>

    <script type="module">
        app.component('v-quantity-changer', {
            template: '#v-quantity-changer-template',

            data() {
                return {
                    quantity: 1
                }
            },

            methods: {
                decrease() {
                    if (this.quantity > 1) this.quantity -= 1;

                    this.$emit('updateQuantity', this.quantity);
                },

                increase() {
                    this.quantity += 1;

                    this.$emit('updateQuantity', this.quantity);
                }
            }
        });
    </script>
@endPushOnce