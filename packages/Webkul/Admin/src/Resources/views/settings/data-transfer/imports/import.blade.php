<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.settings.data-transfer.imports.import.title')
    </x-slot:title>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
            @lang('admin::app.settings.data-transfer.imports.import.title')
        </p>
    </div>

    <!-- Import Vue Compontent -->
    <v-import />

    @pushOnce('scripts')
        <script type="text/x-template" id="v-import-template">
            <!-- Body Content -->
            <div class="grid gap-2.5 mt-3.5 p-5 max-xl:flex-wrap box-shadow">
                <div
                    class="flex place-content-between items-center w-full p-3 bg-orange-50 border border-orange-200 rounded-sm"
                    v-if="importResource.state == 'pending'"
                >
                    <p class="flex gap-2 items-center">
                        <i class="icon-information text-2xl text-orange-600 bg-orange-200 rounded-full"></i>

                        Click on Validate Data to check your import.
                    </p>

                    <button
                        class="primary-button place-self-start"
                        @click="validate"
                    >
                        @lang('admin::app.settings.data-transfer.imports.import.validate')
                    </button>
                </div>

                <div
                    class="flex place-content-between w-full p-3 border rounded-sm"
                    :class="isValid ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'"
                    v-else-if="importResource.state == 'validated'"
                >
                    <!-- Stats -->
                    <div class="grid gap-2">
                        <p
                            class="flex gap-2 items-center mb-2"
                            v-if="isValid"
                        >
                            <i class="icon-done h-fit rounded-full bg-green-200 text-2xl text-green-600"></i>

                            Your import is valid. Click on Import to start the import process.
                        </p>

                        <p
                            class="flex gap-2 items-center "
                            v-else
                        >
                            <i class="icon-done h-fit rounded-full bg-red-200 text-2xl text-red-600"></i>

                            Your import is invalid. Please fix the following errors and try again.
                        </p>
                        
                        <p class="flex gap-2 items-center">
                            <i
                                class="icon-information text-2xl rounded-full"
                                :class="isValid ? 'bg-green-200 text-green-600' : 'bg-red-200 text-red-600'"
                            ></i>

                            <span class="text-gray-800 font-medium">Total Rows Processed:</span>

                            @{{ importResource.processed_rows_count }}
                        </p>

                        <p class="flex gap-2 items-center">
                            <i
                                class="icon-information text-2xl rounded-full"
                                :class="isValid ? 'bg-green-200 text-green-600' : 'bg-red-200 text-red-600'"
                            ></i>

                            <span class="text-gray-800 font-medium">Total Invalid Rows:</span>

                            @{{ importResource.invalid_rows_count }}
                        </p>

                        <p class="flex gap-2 items-center">
                            <i
                                class="icon-information text-2xl rounded-full"
                                :class="isValid ? 'bg-green-200 text-green-600' : 'bg-red-200 text-red-600'"
                            ></i>

                            <span class="text-gray-800 font-medium">Total Errors:</span>

                            @{{ importResource.errors_count }}
                        </p>

                        <div class="flex gap-2 place-items-start">
                            <i class="icon-information bg-red-200 text-red-600 text-2xl rounded-full"></i>

                            <div class="grid gap-2">
                                <p v-for="error in importResource.errors">
                                    @{{ error[0].message }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <button
                        class="primary-button place-self-start"
                        v-if="isValid"
                        @click="start"
                    >
                        @lang('admin::app.settings.data-transfer.imports.import.title')
                    </button>
                </div>

                <div
                    class="grid gap-2 w-full p-3 bg-green-50 border border-green-200 rounded-sm"
                    v-else-if="importResource.state == 'processing' || importResource.state == 'linking'"
                >
                    <p class="flex gap-2 items-center">
                        <i class="icon-information rounded-full bg-green-200 text-2xl text-green-600"></i>

                        <span v-if="importResource.state == 'processing'">
                            Import In Progress
                        </span>

                        <span v-else>
                            Linking Resources In Progress
                        </span>
                    </p>

                    <div class="w-full bg-green-200 rounded-sm h-5 dark:bg-green-700">
                        <div
                            class="bg-green-600 h-5 rounded-sm"
                            :style="{ 'width': stats.progress + '%' }"
                        ></div>
                    </div>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">Progress:</span>

                        @{{ stats.progress }}%
                    </p>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">Total Batches:</span>

                        @{{ stats.batches.total }}
                    </p>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">Total Batches Completed:</span>

                        @{{ stats.batches.completed }}
                    </p>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">Total Records Created:</span>

                        @{{ stats.summary.created }}
                    </p>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">Total Records Updated:</span>

                        @{{ stats.summary.updated }}
                    </p>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">Total Records Deleted:</span>

                        @{{ stats.summary.deleted }}
                    </p>
                </div>

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

                            Congratulations! Your import was successful.
                        </p>

                        <p class="flex gap-2 items-center">
                            <i class="icon-information text-2xl text-green-600 bg-green-200 rounded-full"></i>
                            
                            <span class="text-gray-800 font-medium">Total Records Created:</span>

                            @{{ importResource.summary.created }}
                        </p>

                        <p class="flex gap-2 items-center">
                            <i class="icon-information text-2xl text-green-600 bg-green-200 rounded-full"></i>
                            
                            <span class="text-gray-800 font-medium">Total Records Updated:</span>

                            @{{ importResource.summary.updated }}
                        </p>

                        <p class="flex gap-2 items-center">
                            <i class="icon-information text-2xl text-green-600 bg-green-200 rounded-full"></i>
                            
                            <span class="text-gray-800 font-medium">Total Records Deleted:</span>

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
                    if (this.importResource.state == 'processing') {
                        this.start();
                    }

                    if (this.importResource.state == 'linking') {
                        this.link();
                    }
                },

                methods: {
                    validate() {
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
                        if (this.import == 'validated') {
                            this.import.state = 'processing';
                        }

                        return;

                        this.$axios.get("{{ route('admin.settings.data_transfer.imports.start', $import->id) }}")
                            .then((response) => {
                                this.importResource = response.data.import;

                                this.stats = response.data.stats;

                                if (this.importResource.state == 'processing') {
                                    this.start();
                                } else if (this.importResource.state == 'linking') {
                                    this.link();
                                }
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                            });
                    },

                    link() {
                        this.$axios.get("{{ route('admin.settings.data_transfer.imports.link', $import->id) }}")
                            .then((response) => {
                                this.importResource = response.data.import;

                                this.stats = response.data.stats;

                                if (this.importResource.state != 'completed') {
                                    this.link();
                                }
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                            });
                    },

                    stats() {
                        this.$axios.get("{{ route('admin.settings.data_transfer.imports.stats', $import->id) }}")
                            .then((response) => {
                                this.importResource = response.data.import;

                                this.stats = response.data.stats;
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