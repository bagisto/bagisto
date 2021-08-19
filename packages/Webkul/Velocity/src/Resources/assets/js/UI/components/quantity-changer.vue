<template>
    <div
        :class="
            `quantity control-group ${
                errors.has(controlName) ? 'has-error' : ''
            }`
        "
    >
        <label
            class="required"
            for="quantity-changer"
            v-text="quantityText"
        ></label>

        <button type="button" class="decrease" @click="decreaseQty()">-</button>

        <input
            :value="qty"
            class="control"
            :name="controlName"
            :v-validate="validations"
            id="quantity-changer"
            :data-vv-as="`&quot;${quantityText}&quot;`"
            readonly
        />

        <button type="button" class="increase" @click="increaseQty()">+</button>

        <span class="control-error" v-if="errors.has(controlName)">{{
            errors.first(controlName)
        }}</span>
    </div>
</template>

<script>
export default {
    template: '#quantity-changer-template',

    inject: ['$validator'],

    props: {
        controlName: {
            type: String,
            default: 'quantity'
        },

        quantity: {
            type: [Number, String],
            default: 1
        },

        quantityText: {
            type: String,
            default: 'Quantity'
        },

        minQuantity: {
            type: [Number, String],
            default: 1
        },

        validations: {
            type: String,
            default: 'required|numeric|min_value:1'
        }
    },

    data: function() {
        return {
            qty: this.quantity
        };
    },

    watch: {
        quantity: function(val) {
            this.qty = val;

            this.$emit('onQtyUpdated', this.qty);
        }
    },

    methods: {
        decreaseQty: function() {
            if (this.qty > this.minQuantity) this.qty = parseInt(this.qty) - 1;

            this.$emit('onQtyUpdated', this.qty);
        },

        increaseQty: function() {
            this.qty = parseInt(this.qty) + 1;

            this.$emit('onQtyUpdated', this.qty);
        }
    }
};
</script>
