<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.settings.data-transfer.imports.import.title')
    </x-slot:title>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
            @lang('admin::app.settings.data-transfer.imports.import.title')
        </p>

        <div class="flex gap-x-2.5 items-center">
            <!-- Create New Tax Rate Button -->
            @if (
                bouncer()->hasPermission('settings.taxes.tax-rates.create')
                && $import->status == 'pending'
            )
                <a href="{{ route('admin.settings.data_transfer.imports.create') }}" class="primary-button">
                    @lang('admin::app.settings.data-transfer.imports.import.button-title')
                </a>
            @endif
        </div>
    </div>

    <!-- Import Vue Compontent -->
    <v-import />

    @pushOnce('scripts')
        <script type="text/x-template" id="v-import-template">
            <!-- Body Content -->
            <div class="grid gap-2.5 mt-3.5 p-5 max-xl:flex-wrap box-shadow">
                <div class="grid gap-2 w-full p-3 bg-green-50 border border-green-200 rounded-sm">
                    <p class="flex gap-2 items-center">
                        <i class="icon-information text-2xl text-green-600 bg-green-200 rounded-full"></i>
                        Import In Progress
                    </p>

                    <div class="w-full bg-green-200 rounded-sm h-5 dark:bg-green-700">
                        <div class="bg-green-600 h-5 rounded-sm" style="width: 45%"></div>
                    </div>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">Total Rows Processed:</span> 10000
                    </p>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">Total Invalid Rows:</span> 0
                    </p>

                    <p class="flex gap-2 items-center">
                        <span class="text-gray-800 font-medium">Total Errors:</span> 0
                    </p>
                </div>

                <div class="flex place-content-between w-full p-3 bg-green-50 border border-green-200 rounded-sm">
                    <div class="grid gap-2">
                        <p class="flex gap-2 items-center">
                            <i class="icon-information text-2xl text-green-600 bg-green-200 rounded-full"></i>
                            <span class="text-gray-800 font-medium">Total Rows Processed:</span> 10000
                        </p>

                        <p class="flex gap-2 items-center">
                            <i class="icon-information text-2xl text-green-600 bg-green-200 rounded-full"></i>
                            <span class="text-gray-800 font-medium">Total Invalid Rows:</span> 0
                        </p>

                        <p class="flex gap-2 items-center">
                            <i class="icon-information text-2xl text-green-600 bg-green-200 rounded-full"></i>
                            <span class="text-gray-800 font-medium">Total Errors:</span> 0
                        </p>
                    </div>

                    <button class="primary-button place-self-start">
                        @lang('admin::app.settings.data-transfer.imports.import.title')
                    </button>
                </div>

                <div class="flex place-content-between w-full p-3 bg-blue-50 border border-blue-200 rounded-sm">
                    Click on Validate Data to check your import.
                </div>

                <div class="flex place-content-between w-full p-3 bg-orange-50 border border-orange-200 rounded-sm">
                    Click on Validate Data to check your import.
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-import', {
                template: '#v-import-template',

                data() {
                    return {
                    };
                },

                methods: {
                    create(params, { resetForm, resetField, setErrors }) {
                        this.$axios.post("{{ route('admin.settings.data_transfer.imports.store') }}", params, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                            .then((response) => {
                                
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    }
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>