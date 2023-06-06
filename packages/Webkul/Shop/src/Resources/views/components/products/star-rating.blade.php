
<v-star-rating {{ $attributes }}></v-star-rating>

@pushOnce("scripts")
    <script type='text/x-template' id='v-star-rating-template'>
        <div class="flex">
            <span 
                v-for='rating in total'
                :class='`{{ $attributes["editable"] }} icon-star-fill cursor-pointer text-[24px] text-${(value >= rating) ? "[#ffb600]" : "[#7D7D7D]"} `'
                v-if='! {{ $attributes["editable"] }}'
            />

            <span
                v-for='rating in total'
                :class='`icon-star-fill cursor-pointer text-[24px] text-${(value >= rating) ? "[#ffb600]" : "[#7D7D7D]"} `'
                @click='set(rating)'
                @mouseover='set(rating)'
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
                    total: [1, 2, 3, 4, 5],

                    value: this.star
                }
            },

            methods: {
                set(val) {
                    return this.value = val;
                }
            }
        })
    </script>
@endPushOnce
