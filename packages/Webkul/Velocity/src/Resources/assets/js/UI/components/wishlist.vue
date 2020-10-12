<template>
    <i
        v-if="isCustomer == 'true'"
        :class="`material-icons ${addClass ? addClass : ''}`"
        @mouseover="isActive ? isActive = !isActive : ''"
        @mouseout="active !== '' && !isActive ? isActive = !isActive : ''">

        {{ isActive ? 'favorite_border' : 'favorite' }}
    </i>
</template>

<script type="text/javascript">
    export default {
        props: [
            'active',
            'addClass',
        ],

        data: function () {
            return {
                isStateChanged: false,
                isActive: this.active,
            }
        }
    }
</script>