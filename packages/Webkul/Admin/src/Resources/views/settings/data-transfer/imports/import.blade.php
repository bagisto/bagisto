<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.settings.data-transfer.imports.import.title')
    </x-slot>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
            @lang('admin::app.settings.data-transfer.imports.import.title')
        </p>

        <div class="flex gap-x-2.5 items-center">
            <!-- Cancel Button -->
            <a
                href="{{ route('admin.settings.data_transfer.imports.index') }}"
                class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
            >
                @lang('admin::app.settings.data-transfer.imports.import.back-btn')
            </a>

            <!-- Save Button -->
            <a
                href="{{ route('admin.settings.data_transfer.imports.edit', $import->id) }}"
                class="primary-button"
            >
                @lang('admin::app.settings.data-transfer.imports.import.edit-btn')
            </a>
        </div>
    </div>

    <!-- Import Vue Compontent -->
    <v-import />

    @pushOnce('scripts')
        <script type="text/x-template" id="v-import-template">
            <!-- Body Content -->
            <div class="grid gap-2.5 mt-3.5 p-5 max-xl:flex-wrap box-shadow">
                <!-- Validate CSV File -->
                <div
                    class="flex place-content-between items-center w-full p-3 bg-orange-50 border border-orange-200 rounded-sm"
                    v-if="importResource.state == 'pending'"
                >
                    <p class="flex gap-2 items-center">
                        <i class="icon-information text-2xl text-orange-600 bg-orange-200 rounded-full"></i>

                        @lang('admin::app.settings.data-transfer.imports.import.validate-info')
                    </p>

                    <button
                        class="primary-button place-self-start"
                        @click="validate"
                    >
                        @lang('admin::app.settings.data-transfer.imports.import.validate')
                    </button>
                </div>

                <!-- Validation In Process -->
                <div
                    class="flex place-content-between items-center w-full p-3 bg-blue-50 border border-blue-200 rounded-sm"
                    v-if="importResource.state == 'validating'"
                >
                    <p class="flex gap-2 items-center">
                        <i class="icon-information text-2xl text-blue-600 bg-blue-200 rounded-full"></i>

                        @lang('admin::app.settings.data-transfer.imports.import.validating-info')

                        <!-- Spinner -->
                        <svg class="animate-spin h-5 w-5 ml-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"  aria-hidden="true" viewBox="0 0 24 24">
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
                    </p>
                </div>

                <!-- Validation Results -->
                <div
                    class="flex place-content-between w-full p-3 border rounded-sm"
                    :class="isValid ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'"
                    v-else-if="importResource.state == 'validated'"
                >
                    <!-- Import Stats -->
                    <div class="grid gap-2">
                        <p
                            class="flex gap-2 items-center mb-2"
                            v-if="isValid"
                        >
                            <i class="icon-done h-fit rounded-full bg-green-200 text-2xl text-green-600"></i>

                            @lang('admin::app.settings.data-transfer.imports.import.validation-success-info')
                        </p>

                        <p
                            class="flex gap-2 items-center"
                            v-else
                        >
                            <i class="icon-cross h-fit rounded-full bg-red-200 text-2xl text-red-600"></i>

                            @lang('admin::app.settings.data-transfer.imports.import.validation-failed-info')
                        </p>
                        
                        <p class="flex gap-2 items-center">
                            <i
                                class="icon-information text-2xl rounded-full"
                                :class="isValid ? 'bg-green-200 text-green-600' : 'bg-red-200 text-red-600'"
                            ></i>

                            <span class="text-gray-800 font-medium">
                                @lang('admin::app.settings.data-transfer.imports.import.total-rows-processed')
                            </span>

                            @{{ importResource.processed_rows_count }}
                        </p>

                        <p class="flex gap-2 items-center">
                            <i
                                class="icon-information text-2xl rounded-full"
                                :class="isValid ? 'bg-green-200 text-green-600' : 'bg-red-200 text-red-600'"
                            ></i>

                            <span class="text-gray-800 font-medium">
                                @lang('admin::app.settings.data-transfer.imports.import.total-invalid-rows')
                            </span>

                            @{{ importResource.invalid_rows_count }}
                        </p>

                        <p class="flex gap-2 items-center">
                            <i
                                class="icon-information text-2xl rounded-full"
                                :class="isValid ? 'bg-green-200 text-green-600' : 'bg-red-200 text-red-600'"
                            ></i>

                            <span class="text-gray-800 font-medium">
                                @lang('admin::app.settings.data-transfer.imports.import.total-errors')
                            </span>

                            @{{ importResource.errors_count }}
                        </p>

                        <div
                            class="flex gap-2 items-center place-items-start"
                            v-if="importResource.errors.length"
                        >
                            <i class="icon-information bg-red-200 text-red-600 text-2xl rounded-full"></i>

                            <div class="grid gap-2">
                                <p
                                    class="break-all"
                                    v-for="error in importResource.errors"
                                >
                                    @{{ error }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <button
                            class="primary-button place-self-start"
                            v-if="isValid"
                            @click="start"
                        >
                            @lang('admin::app.settings.data-transfer.imports.import.title')
                        </button>

                        <a
                            class="secondary-button place-self-start"
                            href="{{ route('admin.settings.data_transfer.imports.download_error_report', $import->id) }}"
                            target="_blank"
                            v-if="importResource.errors_count"
                        >
                            @lang('admin::app.settings.data-transfer.imports.import.download-error-report')
                        </a>
                    </div>
                </div>

                <!-- Import In Process -->
                <div
                    class="grid gap-2 w-full p-3 bg-green-50 border border-green-200 rounded-sm"
                    v-else-if="importResource.state == 'processing'"
                >
                    <p class="flex gap-2 items-center">
                        <i class="icon-information rounded-full bg-green-200 text-2xl text-green-600"></i>

                        @lang('admin::app.settings.data-transfer.imports.import.importing-info')
                    </p>

                    <div class="w-full bg-green-200 rounded-sm h-5 dark:bg-green-700">
                        <div
                            class="bg-green-600 h-5 rounded-sm"
                            :style="{ 'width': stats.progress + '%' }"
                        ></div>
                    </div>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">
                            @lang('admin::app.settings.data-transfer.imports.import.progress')
                        </span>

                        @{{ stats.progress }}%
                    </p>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">
                            @lang('admin::app.settings.data-transfer.imports.import.total-batches')
                        </span>

                        @{{ stats.batches.total }}
                    </p>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">
                            @lang('admin::app.settings.data-transfer.imports.import.completed-batches')
                        </span>

                        @{{ stats.batches.completed }}
                    </p>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">
                            @lang('admin::app.settings.data-transfer.imports.import.total-created')
                        </span>

                        @{{ stats.summary.created }}
                    </p>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">
                            @lang('admin::app.settings.data-transfer.imports.import.total-updated')
                        </span>

                        @{{ stats.summary.updated }}
                    </p>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">
                            @lang('admin::app.settings.data-transfer.imports.import.total-deleted')
                        </span>

                        @{{ stats.summary.deleted }}
                    </p>
                </div>

                <!-- Linking In Process -->
                <div
                    class="grid gap-2 w-full p-3 bg-green-50 border border-green-200 rounded-sm"
                    v-else-if="importResource.state == 'linking'"
                >

                    <p class="flex gap-2 items-center">
                        <i class="icon-information rounded-full bg-green-200 text-2xl text-green-600"></i>

                        @lang('admin::app.settings.data-transfer.imports.import.linking-info')
                    </p>

                    <div class="w-full bg-green-200 rounded-sm h-5 dark:bg-green-700">
                        <div
                            class="bg-green-600 h-5 rounded-sm"
                            :style="{ 'width': stats.progress + '%' }"
                        ></div>
                    </div>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">
                            @lang('admin::app.settings.data-transfer.imports.import.progress')
                        </span>

                        @{{ stats.progress }}%
                    </p>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">
                            @lang('admin::app.settings.data-transfer.imports.import.total-batches')
                        </span>

                        @{{ stats.batches.total }}
                    </p>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">
                            @lang('admin::app.settings.data-transfer.imports.import.completed-batches')
                        </span>

                        @{{ stats.batches.completed }}
                    </p>
                </div>

                <!-- Indexing In Process -->
                <div
                    class="grid gap-2 w-full p-3 bg-green-50 border border-green-200 rounded-sm"
                    v-else-if="importResource.state == 'indexing'"
                >

                    <p class="flex gap-2 items-center">
                        <i class="icon-information rounded-full bg-green-200 text-2xl text-green-600"></i>

                        @lang('admin::app.settings.data-transfer.imports.import.indexing-info')
                    </p>

                    <div class="w-full bg-green-200 rounded-sm h-5 dark:bg-green-700">
                        <div
                            class="bg-green-600 h-5 rounded-sm"
                            :style="{ 'width': stats.progress + '%' }"
                        ></div>
                    </div>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">
                            @lang('admin::app.settings.data-transfer.imports.import.progress')
                        </span>

                        @{{ stats.progress }}%
                    </p>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">
                            @lang('admin::app.settings.data-transfer.imports.import.total-batches')
                        </span>

                        @{{ stats.batches.total }}
                    </p>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">
                            @lang('admin::app.settings.data-transfer.imports.import.completed-batches')
                        </span>

                        @{{ stats.batches.completed }}
                    </p>
                </div>

                <!-- Import Completed -->
                <div
                    class="flex place-content-between w-full p-3 bg-green-50 border border-green-200 rounded-sm"
                    v-else-if="importResource.state == 'completed'"
                >
                    <!-- Stats -->
                    <div class="grid gap-2">
                        <p
                            class="flex gap-2 items-center mb-2 text-base"
                            v-if="isValid"
                        >
                            <i class="icon-done h-fit rounded-full bg-green-200 text-2xl text-green-600"></i>

                            @lang('admin::app.settings.data-transfer.imports.import.imported-info')
                        </p>

                        <p class="flex gap-2 items-center">
                            <i class="icon-information text-2xl text-green-600 bg-green-200 rounded-full"></i>
                            
                            <span class="text-gray-800 font-medium">
                                @lang('admin::app.settings.data-transfer.imports.import.total-created')
                            </span>

                            @{{ importResource.summary.created }}
                        </p>

                        <p class="flex gap-2 items-center">
                            <i class="icon-information text-2xl text-green-600 bg-green-200 rounded-full"></i>
                            
                            <span class="text-gray-800 font-medium">
                                @lang('admin::app.settings.data-transfer.imports.import.total-updated')
                            </span>

                            @{{ importResource.summary.updated }}
                        </p>

                        <p class="flex gap-2 items-center">
                            <i class="icon-information text-2xl text-green-600 bg-green-200 rounded-full"></i>
                            
                            <span class="text-gray-800 font-medium">
                                @lang('admin::app.settings.data-transfer.imports.import.total-deleted')
                            </span>

                            @{{ importResource.summary.deleted }}
                        </p>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-import', {
                template: '#v-import-template',

                data() {
                    return {
                        importResource: @json($import),

                        isValid: "{{ $isValid }}",

                        stats: @json($stats),
                    };
                },

                mounted() {
                    if (this.importResource.process_in_queue) {
                        if (
                            this.importResource.state == 'processing'
                            || this.importResource.state == 'linking'
                            || this.importResource.state == 'indexing'
                        ) {
                            this.getStats();
                        }
                    } else {
                        if (this.importResource.state == 'processing') {
                            this.start();
                        }

                        if (this.importResource.state == 'linking') {
                            this.link();
                        }

                        if (this.importResource.state == 'indexing') {
                            this.index();
                        }
                    }
                },

                methods: {
                    validate() {
                        this.importResource.state = 'validating';

                        this.$axios.get("{{ route('admin.settings.data_transfer.imports.validate', $import->id) }}")
                            .then((response) => {
                                this.importResource = response.data.import;

                                this.isValid = response.data.is_valid;
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                            });
                    },

                    start() {
                        this.importResource.state = 'processing';

                        this.$axios.get("{{ route('admin.settings.data_transfer.imports.start', $import->id) }}")
                            .then((response) => {
                                this.importResource = response.data.import;

                                this.stats = response.data.stats;

                                if (this.importResource.process_in_queue) {
                                    this.getStats();
                                } else {
                                    if (this.importResource.state == 'processing') {
                                        this.start();
                                    } else if (this.importResource.state == 'linking') {
                                        this.link();
                                    } else if (this.importResource.state == 'indexing') {
                                        this.index();
                                    }
                                }
                            })
                            .catch(error => {
                            this.importResource.state = 'validated';

                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                            });
                    },

                    link() {
                        this.$axios.get("{{ route('admin.settings.data_transfer.imports.link', $import->id) }}")
                            .then((response) => {
                                this.importResource = response.data.import;

                                this.stats = response.data.stats;

                                if (this.importResource.state == 'linking') {
                                    this.link();
                                } else if (this.importResource.state == 'indexing') {
                                    this.index();
                                }
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                            });
                    },

                    index() {
                        this.$axios.get("{{ route('admin.settings.data_transfer.imports.index_data', $import->id) }}")
                            .then((response) => {
                                this.importResource = response.data.import;

                                this.stats = response.data.stats;

                                if (this.importResource.state == 'indexing') {
                                    this.index();
                                }
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                            });
                    },

                    getStats() {
                        let state = 'processed';

                        if (this.importResource.state == 'linking') {
                            state = 'linked';
                        } else if (this.importResource.state == 'indexing') {
                            state = 'indexed';
                        }

                        this.$axios.get("{{ route('admin.settings.data_transfer.imports.stats', $import->id) }}/" + state)
                            .then((response) => {
                                this.importResource = response.data.import;

                                this.stats = response.data.stats;

                                if (this.importResource.state != 'completed') {
                                    setTimeout(() => {
                                        this.getStats();
                                    }, 1000);
                                }
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                            });
                    }
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>