<v-full-page-cache-flush depend-name="{{ $field->getDependFieldName() }}"></v-full-page-cache-flush>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-full-page-cache-flush-template"
    >
        <div v-if="isVisible">
            <p class="mb-3 text-xs text-gray-600 dark:text-gray-300">
                @lang('admin::app.configuration.index.cache-management.full-page-cache.settings.flush-info')
            </p>

            <button
                type="button"
                class="cursor-pointer rounded-md border border-red-300 bg-white px-3 py-1.5 text-xs font-semibold text-red-600 transition-all hover:bg-red-600 hover:text-white disabled:cursor-not-allowed disabled:opacity-50 dark:border-red-700 dark:bg-gray-900 dark:text-red-400 dark:hover:bg-red-600 dark:hover:text-white"
                :disabled="isFlushing"
                @click="flush"
            >
                <span
                    class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-current border-t-transparent align-middle"
                    v-if="isFlushing"
                ></span>

                @lang('admin::app.configuration.index.cache-management.full-page-cache.settings.flush')
            </button>
        </div>
    </script>

    <script type="module">
        app.component('v-full-page-cache-flush', {
            template: '#v-full-page-cache-flush-template',

            props: ['dependName'],

            data() {
                return {
                    isFlushing: false,

                    isVisible: true,
                };
            },

            mounted() {
                if (! this.dependName) {
                    return;
                }

                const dependElement = document.getElementById(this.dependName);

                if (! dependElement) {
                    return;
                }

                dependElement.addEventListener('change', (event) => {
                    this.isVisible = event.target.checked;
                });

                dependElement.dispatchEvent(new Event('change'));
            },

            methods: {
                flush() {
                    if (this.isFlushing) {
                        return;
                    }

                    this.isFlushing = true;

                    this.$axios.post("{{ route('admin.configuration.cache-management.execute') }}", {
                            action: 'clear-page-cache',
                        })
                        .then((response) => {
                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: response.data.message,
                            });
                        })
                        .catch((error) => {
                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: error.response?.data?.message
                                    ?? '@lang("admin::app.configuration.index.cache-management.console-unknown-error")',
                            });
                        })
                        .finally(() => {
                            this.isFlushing = false;
                        });
                },
            },
        });
    </script>
@endPushOnce
