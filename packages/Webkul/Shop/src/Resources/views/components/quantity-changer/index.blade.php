@props([
    'name'     => '',
    'value'    => 1,
    'minValue' => 1,
])

<v-quantity-changer
    {{ $attributes->merge(['class' => 'flex items-center border border-navyBlue']) }}
    name="{{ $name }}"
    value="{{ $value }}"
    min-value="{{ $minValue }}"
>
</v-quantity-changer>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-quantity-changer-template"
    >
        <div>
            <button
                type="button"
                class="icon-minus cursor-pointer text-2xl focus-visible:ring-2 focus-visible:ring-navyBlue focus-visible:ring-offset-2 focus-visible:outline-none rounded bg-transparent border-0"
                aria-label="@lang('shop::app.components.quantity-changer.decrease-quantity')"
                @click="decrease"
            ></button>

            <p class="w-2.5 select-none text-center max-sm:text-sm">
                @{{ quantity }}
            </p>
            
            <button
                type="button"
                class="icon-plus cursor-pointer text-2xl focus-visible:ring-2 focus-visible:ring-navyBlue focus-visible:ring-offset-2 focus-visible:outline-none rounded bg-transparent border-0"
                aria-label="@lang('shop::app.components.quantity-changer.increase-quantity')"
                @click="increase"
            ></button>

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

            props:['name', 'value', 'minValue'],

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
                    if (this.quantity > this.minValue) {
                        this.quantity -= 1;

                        this.$emit('change', this.quantity);
                    }
                },
            }
        });
    </script>
@endpushOnce
