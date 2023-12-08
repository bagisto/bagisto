<v-button {{ $attributes }}></v-button>

@pushOnce('scripts')
    <script type="text/x-template" id="v-button-template">
        <div>
            <button
                v-if="! isLoading"
                {{ $attributes->merge(['class' => 'aria-diabled']) }}
            >
                @{{ title }}
            </button>

            <button
                v-else
                {{ $attributes->merge(['class' => '']) }}
            >
                <!-- Spinner -->
                <svg class="animate-spin h-5 w-5 text-blue" xmlns="http://www.w3.org/2000/svg" fill="none"  aria-hidden="true" viewBox="0 0 24 24">
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    >
                    </circle>

                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    >
                    </path>
                </svg>

                <span class="" v-text="title"></span>
            </button>
        </div>
    </script>

    <script type="module">
        app.component('v-button', {
            template: '#v-button-template',

            props: {
                loading: Boolean,
                buttonType: String,
                title: String,
                class: String,
            },

            data() {
                return {
                    isLoading: this.loading,
                }
            },
        });
    </script>
@endPushOnce
