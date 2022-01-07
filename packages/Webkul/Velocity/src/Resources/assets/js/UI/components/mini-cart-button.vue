<template>
    <button
        type="button"
        id="mini-cart"
        @click="toggleMiniCart"
        :class="
            `btn btn-link disable-box-shadow ${
                itemCount == 0 ? 'cursor-not-allowed' : ''
            }`
        "
    >
        <div class="mini-cart-content">
            <i class="material-icons-outlined text-down-3">shopping_cart</i>
            <span class="badge" v-text="itemCount" v-if="itemCount != 0"></span>
            <span class="fs18 fw6 cart-text" v-text="cartText"></span>
        </div>

        <div class="down-arrow-container">
            <span class="rango-arrow-down"></span>
        </div>
    </button>
</template>

<script>
export default {
    props: ['itemCount', 'cartText'],

    methods: {
        toggleMiniCart: function() {
            let modal = $('#cart-modal-content')[0];

            if (modal) modal.classList.toggle('hide');

            let accountModal = $('.account-modal')[0];

            if (accountModal) accountModal.classList.add('hide');

            event.stopPropagation();
        }
    }
};
</script>
