@props([
    'name'     => '',
    'value'    => 1,
    'minValue' => 1,
])

<v-quantity-changer
    {{ $attributes }}
    name="{{ $name }}"
    value="{{ $value }}"
    min-value="{{ $minValue }}"
>
</v-quantity-changer>

@pushOnce('scripts')
<script type="text/x-template" id="v-quantity-changer-template">

<div class="flex items-center border-2 border-[#DFAA8B] rounded-[50px] overflow-hidden h-[47px]">

    <button type="button"
        class="pl-3 pr-2 text-[#371E0F] text-xl font-semibold select-none"
        @click="decrease">
        −
    </button>

    <span class="w-10 text-center text-[#371E0F] font-roboto">
        @{{ quantity }}
    </span>

    <button type="button"
        class="pl-2 pr-3 text-[#371E0F] text-xl font-semibold select-none"
        @click="increase">
        +
    </button>

    <v-field
        type="hidden"
        :name="name"
        v-model="quantity">
    </v-field>

</div>

</script>

<script type="module">
app.component("v-quantity-changer", {
    template: '#v-quantity-changer-template',

    props:['name','value','minValue'],

    data() {
        return {
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