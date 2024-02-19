<v-modal-confirm ref="confirmModal"></v-modal-confirm>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-modal-confirm-template"
    >
        <div>
            <transition
                tag="div"
                name="modal-overlay"
                enter-class="ease-out duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-class="ease-in duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-50 transition-opacity z-[10002]"
                    v-show="isOpen"
                ></div>
            </transition>

            <transition
                tag="div"
                name="modal-content"
                enter-class="ease-out duration-300"
                enter-from-class="opacity-0 translate-y-4 md:translate-y-0 md:scale-95"
                enter-to-class="opacity-100 translate-y-0 md:scale-100"
                leave-class="ease-in duration-200"
                leave-from-class="opacity-100 translate-y-0 md:scale-100"
                leave-to-class="opacity-0 translate-y-4 md:translate-y-0 md:scale-95"
            >
                <div
                    class="fixed inset-0 z-[10002] transform transition overflow-y-auto"
                    v-if="isOpen"
                >
                    <div class="flex min-h-full items-end justify-center p-5 sm:items-center sm:p-0">
                        <div class="w-full max-w-[400px] z-[999] absolute left-1/2 top-1/2 rounded-lg bg-white dark:bg-gray-900 box-shadow max-md:w-[90%] -translate-x-1/2 -translate-y-1/2">
                            <div class="flex justify-between items-center gap-2.5 px-4 py-3 border-b dark:border-gray-800 text-lg text-gray-800 dark:text-white font-bold">
                                @{{ title }}
                            </div>

                            <div class="px-4 py-3 text-gray-600 dark:text-gray-300 text-left">
                                @{{ message }}
                            </div>

                            <div class="flex gap-2.5 justify-end px-4 py-2.5">
                                <button type="button" class="transparent-button" @click="disagree">
                                    @{{ options.btnDisagree }}
                                </button>

                                <button type="button" class="primary-button" @click="agree">
                                    @{{ options.btnAgree }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </script>

    <script type="module">
        app.component('v-modal-confirm', {
            template: '#v-modal-confirm-template',

            data() {
                return {
                    isOpen: false,

                    title: '',

                    message: '',

                    options: {
                        btnDisagree: '',
                        btnAgree: '',
                    },

                    agreeCallback: null,

                    disagreeCallback: null,
                };
            },

            created() {
                this.registerGlobalEvents();
            },

            methods: {
                open({
                    title = "@lang('admin::app.components.modal.confirm.title')",
                    message = "@lang('admin::app.components.modal.confirm.message')",
                    options = {
                        btnDisagree: "@lang('admin::app.components.modal.confirm.disagree-btn')",
                        btnAgree: "@lang('admin::app.components.modal.confirm.agree-btn')",
                    },
                    agree = () => {},
                    disagree = () => {},
                }) {
                    this.isOpen = true;

                    document.body.style.overflow = 'hidden';

                    this.title = title;

                    this.message = message;

                    this.options = options;

                    this.agreeCallback = agree;

                    this.disagreeCallback = disagree;
                },

                disagree() {
                    this.isOpen = false;

                    document.body.style.overflow = 'auto';

                    this.disagreeCallback();
                },

                agree() {
                    this.isOpen = false;

                    document.body.style.overflow = 'auto';

                    this.agreeCallback();
                },

                registerGlobalEvents() {
                    this.$emitter.on('open-confirm-modal', this.open);
                },
            }
        });
    </script>
@endPushOnce