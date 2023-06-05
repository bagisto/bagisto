
<v-star-rating {{ $attributes }}></v-star-rating>

@pushOnce("scripts")
    <script type='text/x-template' id='v-star-rating-template'>
        <div class="flex">
            <span 
                v-for='rating in totalRating'
                :class='`as {{ $attributes["editable"] }} icon-star-fill cursor-pointer text-[24px] text-${(value >= rating) ? "[#ffb600]" : "[#7D7D7D]"} `'
                v-if='! {{ $attributes["editable"] }}'
            />

            <span
                v-for='rating in totalRating'
                :class='`n icon-star-fill cursor-pointer text-[24px] text-${(value >= rating) ? "[#ffb600]" : "[#7D7D7D]"} `'
                @click='setRating(rating)'
                @mouseover='setRating(rating)'
                v-if='{{ $attributes["editable"] }}'
            />

            <input 
                type='hidden'
                name='star_rating'
                v-model='value'
            >
        </div>
    </script>

    <script type='module'>
        app.component('v-star-rating', {
            template: '#v-star-rating-template',

            props: ['star'],

            data() {
                return {
                    totalRating: [1, 2, 3, 4, 5],

                    value: this.star
                }
            },

            methods: {
                setRating(val) {
                    return this.value = val;
                }
            }
        })
    </script>
@endPushOnce