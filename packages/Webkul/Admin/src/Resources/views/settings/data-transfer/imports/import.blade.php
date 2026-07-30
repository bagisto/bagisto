<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.data-transfer.imports.import.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.settings.data-transfer.imports.import.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- Back Button -->
            <a
                href="{{ route('admin.settings.data_transfer.imports.index') }}"
                class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
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

    @php
        /**
         * Each import walks a different set of phases, so the stepper is built
         * from the import itself rather than from a fixed list. A delete run has
         * no images to fetch and nothing to link; an importer that carries no
         * image references skips that phase too.
         */
        $isDelete = $import->action === 'delete';

        $prefix = 'admin::app.settings.data-transfer.imports.import.';

        $importSteps = [trans($prefix.'step-validate')];

        $stepMap = [
            'pending' => 0,
            'validating' => 0,
            'validated' => 1,
        ];

        /**
         * Images are fetched up-front, in a phase of their own, so the create
         * step never blocks on the network — the create jobs only ever read
         * files that are already on disk.
         */
        if (! $isDelete && $hasImagePhase) {
            $importSteps[] = trans($prefix.'step-download');

            $stepMap['downloading'] = count($importSteps) - 1;
        }

        $importSteps[] = trans($prefix.($isDelete ? 'step-delete' : 'step-create'));

        $stepMap['processing'] = count($importSteps) - 1;

        if ($hasLinkPhase) {
            $importSteps[] = trans($prefix.'step-link');
        }

        $stepMap['linking'] = count($importSteps) - 1;

        if ($hasIndexPhase) {
            $importSteps[] = trans($prefix.'step-index');
        }

        $stepMap['indexing'] = count($importSteps) - 1;

        $stepMap['completed'] = count($importSteps);
    @endphp

    <!-- Import Vue Component -->
    <v-import />

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-import-template"
        >
            <div class="mt-3.5 flex flex-col gap-2.5">
                <!-- Stepper -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <ol class="flex items-start pb-7">
                        <template v-for="(step, index) in steps">
                            <!-- Step Node -->
                            <li class="relative flex shrink-0 flex-col items-center">
                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-full border-2 text-sm font-semibold transition-all duration-300"
                                    :class="stepNodeClass(index)"
                                >
                                    <!-- Drawn rather than set from the icon font: the font's tick carries its own padding, which leaves it sitting off-centre once it is reversed out of a filled disc. -->
                                    <svg
                                        class="h-5 w-5"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="3"
                                        aria-hidden="true"
                                        v-if="index < currentStep"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M5 13l4 4L19 7"
                                        ></path>
                                    </svg>

                                    <svg
                                        class="h-4 w-4 animate-spin"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        aria-hidden="true"
                                        v-else-if="index === currentStep && isWorking"
                                    >
                                        <circle
                                            class="opacity-25"
                                            cx="12"
                                            cy="12"
                                            r="10"
                                            stroke="currentColor"
                                            stroke-width="4"
                                        ></circle>

                                        <path
                                            class="opacity-75"
                                            fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                        ></path>
                                    </svg>

                                    <span v-else>@{{ index + 1 }}</span>
                                </span>

                                <span
                                    class="absolute left-1/2 top-full mt-2 w-20 -translate-x-1/2 text-center text-xs font-medium leading-tight transition-colors max-sm:hidden"
                                    :class="index <= currentStep ? 'text-gray-800 dark:text-white' : 'text-gray-400 dark:text-gray-500'"
                                >
                                    @{{ step }}
                                </span>
                            </li>

                            <!-- Connector -->
                            <li
                                class="mt-5 h-0.5 min-w-6 flex-1 rounded-full transition-colors duration-300"
                                :class="index < currentStep ? 'bg-green-600' : 'bg-gray-200 dark:bg-gray-700'"
                                v-if="index < steps.length - 1"
                            ></li>
                        </template>
                    </ol>
                </div>

                <!-- Phase Panel -->
                <div class="box-shadow grid gap-2.5 rounded bg-white p-4 dark:bg-gray-900">
                    <!-- Ready To Validate -->
                    <div
                        class="flex w-full place-content-between items-center rounded-sm border border-orange-200 bg-orange-50 p-3 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                        v-if="importResource.state == 'pending'"
                    >
                        <p class="flex items-center gap-2">
                            <i class="icon-information rounded-full bg-orange-200 text-2xl text-orange-600 dark:!text-orange-600"></i>

                            @lang('admin::app.settings.data-transfer.imports.import.validate-info')
                        </p>

                        <button
                            class="primary-button place-self-start"
                            @click="validate"
                        >
                            @lang('admin::app.settings.data-transfer.imports.import.validate')
                        </button>
                    </div>

                    <!-- Validating -->
                    <div
                        class="grid w-full gap-2 rounded-sm border border-blue-200 bg-blue-50 p-3 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                        v-else-if="importResource.state == 'validating'"
                    >
                        <p class="flex items-center gap-2">
                            <i class="icon-information rounded-full bg-blue-200 text-2xl text-blue-600 dark:!text-blue-600"></i>

                            @lang('admin::app.settings.data-transfer.imports.import.validating-info')
                        </p>

                        <div class="h-5 w-full rounded-sm bg-blue-200 dark:bg-blue-700">
                            <div
                                class="h-5 rounded-sm bg-blue-600 transition-all duration-300"
                                :style="{ 'width': validationPercent + '%' }"
                            ></div>
                        </div>

                        <p
                            class="flex items-center gap-2"
                            v-if="validationProgress.total"
                        >
                            <span class="font-medium text-gray-800 dark:text-white">
                                @{{ validationProgress.processed }} / @{{ validationProgress.total }}
                            </span>

                            @lang('admin::app.settings.data-transfer.imports.import.rows-validated')

                            <span>(@{{ validationPercent }}%)</span>
                        </p>
                    </div>

                    <!-- Validation Results -->
                    <div
                        class="flex w-full place-content-between rounded-sm border p-3"
                        :class="isValid ? 'border-green-200 bg-green-50 dark:bg-gray-900 dark:border-gray-800' : 'border-red-200 bg-red-50 dark:bg-gray-900 dark:border-gray-800'"
                        v-else-if="importResource.state == 'validated'"
                    >
                        <!-- Import Stats -->
                        <div class="grid gap-2">
                            <p
                                class="mb-2 flex items-center gap-2 dark:text-white"
                                v-if="isValid && ! importResource.errors_count"
                            >
                                <i class="icon-done h-fit rounded-full bg-green-200 text-2xl text-green-600 dark:!text-green-600"></i>

                                @lang('admin::app.settings.data-transfer.imports.import.validation-success-info')
                            </p>

                            <p
                                class="mb-2 flex items-center gap-2 dark:text-white"
                                v-else-if="isValid"
                            >
                                <i class="icon-information h-fit rounded-full bg-orange-200 text-2xl text-orange-600 dark:!text-orange-600"></i>

                                @lang('admin::app.settings.data-transfer.imports.import.validation-partial-info')
                            </p>

                            <p
                                class="flex items-center gap-2 dark:text-white"
                                v-else
                            >
                                <i class="icon-cross h-fit rounded-full bg-red-200 text-2xl text-red-600 dark:!text-red-600"></i>

                                @lang('admin::app.settings.data-transfer.imports.import.validation-failed-info')
                            </p>

                            <p class="flex items-center gap-2 dark:text-white">
                                <i
                                    class="icon-information rounded-full text-2xl"
                                    :class="isValid ? 'bg-green-200 text-green-600 dark:!text-green-600' : 'bg-red-200 text-red-600 dark:!text-red-600'"
                                ></i>

                                <span class="font-medium text-gray-800 dark:text-white">
                                    @lang('admin::app.settings.data-transfer.imports.import.total-rows-processed')
                                </span>

                                @{{ importResource.processed_rows_count }}
                            </p>

                            <p class="flex items-center gap-2 dark:text-white">
                                <i
                                    class="icon-information rounded-full text-2xl"
                                    :class="isValid ? 'bg-green-200 text-green-600 dark:!text-green-600' : 'bg-red-200 text-red-600 dark:!text-red-600'"
                                ></i>

                                <span class="font-medium text-gray-800 dark:text-white">
                                    @lang('admin::app.settings.data-transfer.imports.import.total-invalid-rows')
                                </span>

                                @{{ importResource.invalid_rows_count }}
                            </p>

                            <p class="flex items-center gap-2 dark:text-white">
                                <i
                                    class="icon-information rounded-full text-2xl"
                                    :class="isValid ? 'bg-green-200 text-green-600 dark:!text-green-600' : 'bg-red-200 text-red-600 dark:!text-red-600'"
                                ></i>

                                <span class="font-medium text-gray-800 dark:text-white">
                                    @lang('admin::app.settings.data-transfer.imports.import.total-errors')
                                </span>

                                @{{ importResource.errors_count }}
                            </p>

                            <div
                                class="flex place-items-start items-center gap-2 dark:text-white"
                                v-if="importResource.errors.length"
                            >
                                <i class="icon-information rounded-full bg-red-200 text-2xl text-red-600 dark:!text-red-600"></i>

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
                                @click="runImport"
                            >
                                @lang('admin::app.settings.data-transfer.imports.import.title')
                            </button>

                            <a
                                class="primary-button place-self-start"
                                href="{{ route('admin.settings.data_transfer.imports.download_error_report', $import->id) }}"
                                target="_blank"
                                v-if="importResource.error_file_path && importResource.errors_count"
                            >
                                @lang('admin::app.settings.data-transfer.imports.import.download-error-report')
                            </a>
                        </div>
                    </div>

                    <!-- Downloading Images -->
                    <div
                        class="grid w-full gap-2 rounded-sm border border-blue-200 bg-blue-50 p-3 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                        v-else-if="importResource.state == 'downloading'"
                    >
                        <p class="flex items-center gap-2">
                            <i class="icon-information rounded-full bg-blue-200 text-2xl text-blue-600 dark:!text-blue-600"></i>

                            @lang('admin::app.settings.data-transfer.imports.import.downloading-info')
                        </p>

                        <div class="h-5 w-full rounded-sm bg-blue-200 dark:bg-blue-700">
                            <div
                                class="h-5 rounded-sm bg-blue-600 transition-all duration-300"
                                :style="{ 'width': imageProgress.progress + '%' }"
                            ></div>
                        </div>

                        <p class="flex items-center gap-2">
                            <span class="font-medium text-gray-800 dark:text-white">
                                @{{ imageProgress.processed }} / @{{ imageProgress.total }}
                            </span>

                            @lang('admin::app.settings.data-transfer.imports.import.images-progress')

                            <span>(@{{ imageProgress.progress }}%)</span>
                        </p>
                    </div>

                    <!-- Import / Linking / Indexing In Process -->
                    <div
                        class="grid w-full gap-2 rounded-sm border border-green-200 bg-green-50 p-3 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                        v-else-if="['processing', 'linking', 'indexing'].includes(importResource.state)"
                    >
                        <p class="flex items-center gap-2">
                            <i class="icon-information rounded-full bg-green-200 text-2xl text-green-600 dark:!text-green-600"></i>

                            <span v-if="importResource.state == 'processing' && isDelete">
                                @lang('admin::app.settings.data-transfer.imports.import.deleting-info')
                            </span>

                            <span v-else-if="importResource.state == 'processing'">
                                @lang('admin::app.settings.data-transfer.imports.import.importing-info')
                            </span>

                            <span v-else-if="importResource.state == 'linking'">
                                @lang('admin::app.settings.data-transfer.imports.import.linking-info')
                            </span>

                            <span v-else>
                                @lang('admin::app.settings.data-transfer.imports.import.indexing-info')
                            </span>
                        </p>

                        <div class="h-5 w-full rounded-sm bg-green-200 dark:bg-green-700">
                            <div
                                class="h-5 rounded-sm bg-green-600 transition-all duration-300"
                                :style="{ 'width': stats.progress + '%' }"
                            ></div>
                        </div>

                        <p class="flex items-center gap-2">
                            <span class="font-medium text-gray-800 dark:text-white">
                                @lang('admin::app.settings.data-transfer.imports.import.progress')
                            </span>

                            @{{ stats.progress }}%
                        </p>

                        <p class="flex items-center gap-2">
                            <span class="font-medium text-gray-800 dark:text-white">
                                @lang('admin::app.settings.data-transfer.imports.import.total-batches')
                            </span>

                            @{{ stats.batches.total }}
                        </p>

                        <p class="flex items-center gap-2">
                            <span class="font-medium text-gray-800 dark:text-white">
                                @lang('admin::app.settings.data-transfer.imports.import.completed-batches')
                            </span>

                            @{{ stats.batches.completed }}
                        </p>

                        <p
                            class="flex items-center gap-2"
                            v-if="importResource.state == 'processing'"
                        >
                            <span class="font-medium text-gray-800 dark:text-white">
                                @lang('admin::app.settings.data-transfer.imports.import.total-created')
                            </span>

                            @{{ stats.summary.created }}
                        </p>

                        <p
                            class="flex items-center gap-2"
                            v-if="importResource.state == 'processing'"
                        >
                            <span class="font-medium text-gray-800 dark:text-white">
                                @lang('admin::app.settings.data-transfer.imports.import.total-updated')
                            </span>

                            @{{ stats.summary.updated }}
                        </p>

                        <p
                            class="flex items-center gap-2"
                            v-if="importResource.state == 'processing'"
                        >
                            <span class="font-medium text-gray-800 dark:text-white">
                                @lang('admin::app.settings.data-transfer.imports.import.total-deleted')
                            </span>

                            @{{ stats.summary.deleted }}
                        </p>
                    </div>

                    <!-- Import Completed -->
                    <div
                        class="flex w-full place-content-between rounded-sm border border-green-200 bg-green-50 p-3 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                        v-else-if="importResource.state == 'completed'"
                    >
                        <!-- Stats -->
                        <div class="grid gap-2">
                            <p class="mb-2 flex items-center gap-2 text-base">
                                <i class="icon-done h-fit rounded-full bg-green-200 text-2xl text-green-600 dark:!text-green-600"></i>

                                @lang('admin::app.settings.data-transfer.imports.import.imported-info')
                            </p>

                            <!-- A run is allowed to continue past a failed batch, so what did not make it in is named rather than folded into the success line. -->
                            <p
                                class="mb-2 flex items-center gap-2 text-base text-orange-700 dark:text-orange-400"
                                v-if="stats.batches?.failed"
                            >
                                <i class="icon-error h-fit rounded-full bg-orange-200 text-2xl text-orange-600 dark:!text-orange-600"></i>

                                @{{ "@lang('admin::app.settings.data-transfer.imports.import.failed-batches')".replace(':count', stats.batches.failed) }}
                            </p>

                            <p class="flex items-center gap-2">
                                <i class="icon-information rounded-full bg-green-200 text-2xl text-green-600 dark:!text-green-600"></i>

                                <span class="font-medium text-gray-800 dark:text-white">
                                    @lang('admin::app.settings.data-transfer.imports.import.total-created')
                                </span>

                                @{{ importResource.summary.created }}
                            </p>

                            <p class="flex items-center gap-2">
                                <i class="icon-information rounded-full bg-green-200 text-2xl text-green-600 dark:!text-green-600"></i>

                                <span class="font-medium text-gray-800 dark:text-white">
                                    @lang('admin::app.settings.data-transfer.imports.import.total-updated')
                                </span>

                                @{{ importResource.summary.updated }}
                            </p>

                            <p class="flex items-center gap-2">
                                <i class="icon-information rounded-full bg-green-200 text-2xl text-green-600 dark:!text-green-600"></i>

                                <span class="font-medium text-gray-800 dark:text-white">
                                    @lang('admin::app.settings.data-transfer.imports.import.total-deleted')
                                </span>

                                @{{ importResource.summary.deleted }}
                            </p>

                            <p
                                class="flex items-center gap-2"
                                v-if="imageProgress.total"
                            >
                                <i class="icon-information rounded-full bg-green-200 text-2xl text-green-600 dark:!text-green-600"></i>

                                <span class="font-medium text-gray-800 dark:text-white">
                                    @lang('admin::app.settings.data-transfer.imports.import.images-downloaded')
                                </span>

                                @{{ imageProgress.processed }} / @{{ imageProgress.total }}
                            </p>
                        </div>
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

                        steps: @json($importSteps),

                        stepMap: @json($stepMap),

                        isDelete: @json($isDelete),

                        hasImagePhase: @json($hasImagePhase),

                        validationProgress: {
                            total: 0,
                            processed: 0,
                            invalid: 0,
                            done: false,
                        },

                        imageProgress: {
                            total: 0,
                            processed: 0,
                            progress: 0,
                            done: false,
                        },
                    };
                },

                computed: {
                    /**
                     * Which stepper node the import is sitting on right now.
                     */
                    currentStep() {
                        return this.stepMap[this.importResource.state] ?? 0;
                    },

                    /**
                     * Is the current phase actually running, as opposed to waiting
                     * for the operator to press something?
                     */
                    isWorking() {
                        return ! ['pending', 'validated', 'completed'].includes(this.importResource.state);
                    },

                    /**
                     * Percentage of rows validated so far.
                     */
                    validationPercent() {
                        if (! this.validationProgress.total) {
                            return 0;
                        }

                        return Math.floor((this.validationProgress.processed / this.validationProgress.total) * 100);
                    },
                },

                mounted() {
                    /**
                     * A phase left running when the page was closed is picked back
                     * up rather than restarted — the import's state records which
                     * one it was in. A fresh import runs itself from here: the
                     * operator already said "import" on the form, so validation,
                     * images and creation follow one another without further
                     * clicks, and only stop for an error.
                     */
                    if (this.importResource.state == 'pending') {
                        this.validate();

                        return;
                    }

                    if (this.importResource.state == 'validated') {
                        this.runImport();

                        return;
                    }

                    if (this.importResource.process_in_queue) {
                        if (this.importResource.state == 'validating') {
                            this.pollQueuedValidate();
                        } else if (this.importResource.state == 'downloading') {
                            this.pollQueuedDownload();
                        } else if (['processing', 'linking', 'indexing'].includes(this.importResource.state)) {
                            this.getStats();
                        }
                    } else {
                        if (this.importResource.state == 'processing') {
                            this.start();
                        } else if (this.importResource.state == 'linking') {
                            this.link();
                        } else if (this.importResource.state == 'indexing') {
                            this.index();
                        }
                    }
                },

                methods: {
                    /**
                     * Tailwind classes for a stepper node: done, active, or pending.
                     */
                    stepNodeClass(index) {
                        if (index < this.currentStep) {
                            return 'border-green-600 bg-green-600 text-white';
                        }

                        if (index === this.currentStep) {
                            return 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400';
                        }

                        return 'border-gray-300 text-gray-400 dark:border-gray-600 dark:text-gray-500';
                    },

                    /**
                     * Validate the file. A background import validates in parallel
                     * across the queue workers — fast, and the page can be left —
                     * while an immediate one validates in short browser-driven
                     * windows so no single request runs long enough to time out.
                     */
                    validate() {
                        this.importResource.state = 'validating';

                        if (this.importResource.process_in_queue) {
                            this.queuedValidate();
                        } else {
                            this.syncValidate();
                        }
                    },

                    /**
                     * Dispatch the parallel queued validation once, then poll it.
                     */
                    queuedValidate() {
                        this.$axios.get("{{ route('admin.settings.data_transfer.imports.validate_queued', $import->id) }}")
                            .then((response) => {
                                this.validationProgress = {
                                    total: response.data.total,
                                    processed: 0,
                                    invalid: 0,
                                    done: false,
                                };

                                this.pollQueuedValidate();
                            })
                            .catch(error => {
                                this.importResource.state = 'pending';

                                this.$emitter.emit('add-flash', { type: 'error', message: error.response?.data?.message });
                            });
                    },

                    pollQueuedValidate() {
                        this.$axios.get("{{ route('admin.settings.data_transfer.imports.validate_status', $import->id) }}")
                            .then((response) => {
                                this.validationProgress = {
                                    total: response.data.total,
                                    processed: response.data.processed,
                                    invalid: 0,
                                    done: response.data.done,
                                };

                                if (! response.data.done) {
                                    setTimeout(() => this.pollQueuedValidate(), 1000);

                                    return;
                                }

                                this.validated(response);
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response?.data?.message });
                            });
                    },

                    /**
                     * Validate one window, then the next, until the file is done.
                     */
                    syncValidate() {
                        this.$axios.get("{{ route('admin.settings.data_transfer.imports.validate_chunk', $import->id) }}")
                            .then((response) => {
                                this.validationProgress = {
                                    total: response.data.total,
                                    processed: response.data.processed,
                                    invalid: response.data.invalid,
                                    done: response.data.done,
                                };

                                if (! response.data.done) {
                                    this.syncValidate();

                                    return;
                                }

                                this.validated(response);
                            })
                            .catch(error => {
                                this.importResource.state = 'pending';

                                this.$emitter.emit('add-flash', { type: 'error', message: error.response?.data?.message });
                            });
                    },

                    /**
                     * Validation is over. A clean file carries straight on into
                     * the import; one with errors stops here so the operator can
                     * read the report and decide what to do.
                     */
                    validated(response) {
                        this.importResource = response.data.import;

                        this.isValid = response.data.is_valid;

                        if (this.isValid) {
                            this.runImport();
                        }
                    },

                    /**
                     * Begin the import. Remote images are fetched first, in their
                     * own phase, so the create step only ever reads local files.
                     */
                    runImport() {
                        if (! this.hasImagePhase) {
                            this.start();

                            return;
                        }

                        this.downloadImages();
                    },

                    downloadImages() {
                        this.importResource.state = 'downloading';

                        if (this.importResource.process_in_queue) {
                            this.queuedDownload();
                        } else {
                            this.syncDownload();
                        }
                    },

                    queuedDownload() {
                        this.$axios.get("{{ route('admin.settings.data_transfer.imports.download_images_queued', $import->id) }}")
                            .then((response) => {
                                if (! response.data.total) {
                                    this.start();

                                    return;
                                }

                                this.imageProgress = {
                                    total: response.data.total,
                                    processed: 0,
                                    progress: 0,
                                    done: false,
                                };

                                this.pollQueuedDownload();
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', { type: 'warning', message: error.response?.data?.message });

                                this.start();
                            });
                    },

                    pollQueuedDownload() {
                        this.$axios.get("{{ route('admin.settings.data_transfer.imports.download_images_status', $import->id) }}")
                            .then((response) => {
                                this.imageProgress = response.data;

                                if (response.data.done) {
                                    this.start();
                                } else {
                                    setTimeout(() => this.pollQueuedDownload(), 1500);
                                }
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response?.data?.message });
                            });
                    },

                    syncDownload() {
                        this.$axios.get("{{ route('admin.settings.data_transfer.imports.download_images', $import->id) }}")
                            .then((response) => {
                                this.imageProgress = response.data;

                                if (response.data.done) {
                                    this.start();
                                } else {
                                    this.syncDownload();
                                }
                            })
                            .catch(error => {
                                /**
                                 * Fetching images is best-effort: on failure carry
                                 * on to creation so the products still import.
                                 */
                                this.$emitter.emit('add-flash', { type: 'warning', message: error.response?.data?.message });

                                this.start();
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
                    },
                },
            })
        </script>
    @endPushOnce
</x-admin::layouts>
