<template>
    <div
        :class="`stars mr5 fs${size ? size : '16'} ${
            pushClass ? pushClass : ''
        }`"
    >
        <input
            v-if="editable"
            type="number"
            :value="showFilled"
            name="rating"
            class="d-none"
        />

        <i
            :class="`rate_star material-icons ${editable ? 'cursor-pointer' : ''}`"
            v-for="(rating, index) in parseInt(
                showFilled != 'undefined' ? showFilled : 3
            )"
            :key="`${index}${Math.random()}`"
            @click="updateRating(index + 1)"
            @mouseover="ratingMouseOver($event)"
        >
            star
        </i>

        <template v-if="!hideBlank">
            <i
                :class="`rate_star material-icons ${editable ? 'cursor-pointer' : ''}`"
                v-for="(blankStar, index) in 5 -
                (showFilled != 'undefined' ? showFilled : 3)"
                :key="`${index}${Math.random()}`"
                @click="updateRating(showFilled + index + 1)"
                @mouseover="ratingMouseOver($event)"
            >
                star_border
            </i>
        </template>
    </div>
</template>

<style lang="scss">
/**
 * Font size 18px till 420px screen width.
 */
@media only screen and (max-width: 420px) {
    .stars .material-icons {
        font-size: 18px;
    }
}

/**
 * Font size 12px till 322px screen width.
 */
@media only screen and (max-width: 322px) {
    .stars .material-icons {
        font-size: 12px;
    }
}
</style>

<script type="text/javascript">
export default {
    props: ['size', 'ratings', 'editable', 'hideBlank', 'pushClass'],

    data: function () {
        return {
            showFilled: this.ratings,
        };
    },

    methods: {
        updateRating: function (index) {
            index = Math.abs(index);
            this.editable ? (this.showFilled = index) : '';
        },
        
        ratingMouseOver: function (event) {
            let stars = document.getElementsByClassName('rate_star');
            
            stars.forEach(function (star,key) {
                star.setAttribute("id",key + 1);
            })

            this.showFilled = event.target.id;
        }
    },
};
</script>
