<template>
    <i
        v-if="isCustomer == 'true'"
        :class="`material-icons ${addClass ? addClass : ''}`"
        @mouseover="isActive ? isActive = !isActive : ''"
        @mouseout="active !== '' && !isActive ? isActive = !isActive : ''">

        {{ isActive ? 'favorite_border' : 'favorite' }}
    </i>

    <a
        v-else
        @click="toggleProductWishlist(productSlug)"
        :class="`unset wishlist-icon ${addClass ? addClass : ''} text-right`">

        <i
            @mouseout="! isStateChanged ? isActive = !isActive : isStateChanged = false"
            @mouseover="! isStateChanged ? isActive = !isActive : isStateChanged = false"
            :class="`material-icons ${addClass ? addClass : ''}`">

            {{ isActive ? 'favorite' : 'favorite_border' }}
        </i>
    </a>
</template>

<script type="text/javascript">
    export default {
        props: [
            'active',
            'addClass',
            'addedText',
            'removeText',
            'isCustomer',
            'productSlug',
        ],

        data: function () {
            return {
                isStateChanged: false,
                isActive: this.active,
            }
        },

        created: function () {
            if (this.isCustomer == 'false') {
                this.isActive = this.isWishlisted(this.productSlug);
            }
        },

        methods: {
            toggleProductWishlist: function (productSlug) {
                var updatedValue = [productSlug];
                let existingValue = this.getStorageValue('wishlist_product');

                if (existingValue) {
                    let valueIndex = existingValue.indexOf(productSlug);

                    if (valueIndex == -1) {
                        this.isActive = true;
                        existingValue.push(productSlug);
                    } else {
                        this.isActive = false;
                        existingValue.splice(valueIndex, 1);
                    }

                    updatedValue = existingValue;
                }

                this.$root.headerItemsCount++;
                this.isStateChanged = true;

                this.setStorageValue('wishlist_product', updatedValue);

                window.showAlert(
                    'alert-success',
                    this.__('shop.general.alert.success'),
                    this.isActive ? this.addedText : this.removeText
                );

                return true;
            },

            isWishlisted: function (productSlug) {
                let existingValue = this.getStorageValue('wishlist_product');

                if (existingValue) {
                    return ! (existingValue.indexOf(productSlug) == -1);
                } else {
                    return false;
                }
            }
        }
    }
</script>