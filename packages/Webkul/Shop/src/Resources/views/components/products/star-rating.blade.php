@props([
    'star',
    'isEditable' => false
])

<v-star-rating
    star="{{ $star }}"
    isEditable="{{ $isEditable }}"
>
</v-star-rating>

@pushOnce("scripts")
    <script type='text/x-template' id='v-star-rating-template'>
        <div class="flex">
            <span
                v-if="isEditable"
                v-for='rating in total'
                :class='`icon-star-fill cursor-pointer text-[24px] text-${(value >= rating) ? "[#ffb600]" : "[#7D7D7D]"} `'
                @click='set(rating)'
                @mouseover='set(rating)'
            />
            
            <span
                v-else
                v-for='rating in total'
                :class='`icon-star-fill text-[24px] text-${(value >= rating) ? "[#ffb600]" : "[#7D7D7D]"} `'
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
            
            props: [
                'star',
                'isEditable'
            ],

            data() {
                return {
                    total: [1, 2, 3, 4, 5],

                    value: @json($star),
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
