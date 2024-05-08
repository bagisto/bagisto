@props([
    'name'     => 'rating',
    'value'    => 0,
    'disabled' => true,
    'size'     => 'text-2xl',
])

<v-star-rating
    {{ $attributes }}
    name="{{ $name }}"
    value="{{ $value }}"
    disabled="{{ $disabled }}"
    size="{{ $size }}"
>
    <x-shop::shimmer.products.reviews.ratings />
</v-star-rating>

@pushOnce("scripts")
    <script
        type="text/x-template"
        id="v-star-rating-template"
    >
        <div class="flex">
            <span
                class="icon-star-fill cursor-pointer"
                :class="this.size"
                role="presentation"
                v-for="rating in availableRatings"
                v-if="! disabled"
                :style="[`color: ${appliedRatings >= rating ? '#ffb600' : '#7d7d7d'}`]"
                @click="change(rating)"
            >
            </span>

            <span
                class="icon-star-fill"
                :class="this.size"
                role="presentation"
                v-for="rating in availableRatings"
                :style="[`color: ${appliedRatings >= rating ? '#ffb600' : '#7d7d7d'}`]"
                v-else
            >
            </span>

            <v-field
                type="hidden"
                :name="name"
                v-model="appliedRatings"
            ></v-field>
        </div>
    </script>

    <script type="module">
        app.component("v-star-rating", {
            template: "#v-star-rating-template",

            props: [
                "name",
                "value",
                "disabled",
                "size",
            ],

            data() {
                return {
                    availableRatings: [1, 2, 3, 4, 5],

                    appliedRatings: this.value,
                };
            },

            methods: {
                change(rating) {
                    this.appliedRatings = rating;

                    this.$emit('change', this.appliedRatings);
                },
            },
        });
    </script>
@endPushOnce
