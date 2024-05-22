@props([
    'average' => 0,
    'total'   => 0,
])

<div {{ $attributes->merge(['class' => 'flex w-max rounded-md border border-zinc-200 px-4 py-2']) }}>
    <v-product-ratings
        {{ $attributes }}
        average="{{ $average }}"
        total="{{ $total }}"
    >
    </v-product-ratings>
</div>

@pushOnce("scripts")
    <script
        type="text/x-template"
        id="v-product-ratings-template"
    >
        <span class="font-semibold text-black">
            @{{ average }}
        </span>
    
        <span class="icon-star-fill -mt-px text-xl text-amber-500"></span>
        
        <span class="mx-1 text-zinc-300">|</span>
        
        <span class="text-black ltr:ml-1 rtl:mr-1">
            @{{ abbreviatedTotal }}

            <span>Ratings</span>
        </span>
    </script>

    <script type="module">
        app.component("v-product-ratings", {
            template: "#v-product-ratings-template",

            props: {
                average: {
                    type: Number,
                    required: true,
                },

                total: {
                    type: Number,
                    required: true,
                },
            },

            data() {
                return {
                };
            },

            computed: {
                starColorClasses() {
                    return {
                        'text-emerald-600': this.average > 4,
                        'text-emerald-500': this.average >= 4 && this.average < 5,
                        'text-emerald-400': this.average >= 3 && this.average < 4,
                        'text-amber-500': this.average >= 2 && this.average < 3,
                        'text-red-500': this.average >= 1 && this.average < 2,
                        'text-gray-400': this.average <= 0,
                    };
                },

                abbreviatedTotal() {
                    if (this.total >= 1000) {
                        return `${(this.total / 1000).toFixed(1)}k`;
                    }

                    return this.total;
                },
            },
        });
    </script>
@endPushOnce
