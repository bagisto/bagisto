@props([
    'name'  => '',
    'value' => 1,
])

<v-quantity-changer
    {{ $attributes->merge(['class' => 'flex border border-navyBlue items-center']) }}
    name="{{ $name }}"
    value="{{ $value }}"
>
</v-quantity-changer>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-quantity-changer-template"
    >
        <div>
            <span 
                class="icon-minus text-2xl cursor-pointer"
                role="button"
                tabindex="0"
                aria-label="@lang('shop::app.components.quantity-changer.decrease-quantity')"
                @click="decrease"
            >
            </span>

            <p
                class="w-2.5 text-center select-none"
                v-text="quantity"
            ></p>
            
            <span 
                class="icon-plus text-2xl cursor-pointer"
                role="button"
                tabindex="0"
                aria-label="@lang('shop::app.components.quantity-changer.increase-quantity')"
                @click="increase"
            >
            </span>

            <v-field
                type="hidden"
                :name="name"
                v-model="quantity"
            ></v-field>
        </div>
    </script>

    <script type="module">
        app.component("v-quantity-changer", {
            template: '#v-quantity-changer-template',

            props:['name', 'value'],

            data() {
                return  {
                    quantity: this.value,
                }
            },

            watch: {
                value() {
                    this.quantity = this.value;
                },
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
