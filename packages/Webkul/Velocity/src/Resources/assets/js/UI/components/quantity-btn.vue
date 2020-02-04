<template>

    <div class="qty-btn unselectable">
        <div class="deduct-btn rango-minus" @click="count--"></div>
        <div class="actual-count">{{ count }}</div>
        <div class="increase-btn rango-plus" @click="count++"></div>
    </div>

</template>

<script type="text/javascript">
    export default {
        data: function () {
            return {
                count: 1,
            }
        },

        watch: {
            count: function (newValue, oldValue) {
                this.count = newValue < 1 ? oldValue : newValue;
            }
        }
    }
</script>