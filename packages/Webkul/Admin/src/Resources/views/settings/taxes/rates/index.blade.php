<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.settings.taxes.rates.index.title')
    </x-slot:title>

    <div class="flex justify-between items-center">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
            @lang('admin::app.settings.taxes.rates.index.title')
        </p>
        
        <div class="flex gap-x-2.5 items-center">
            <!-- Tax rate import -->
            <v-tax-rates-import>
                <button
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                >
                    @lang('import')
                </button>
            </v-tax-rates-import>

            <!-- Tax Rate Export -->
            <x-admin::datagrid.export src="{{ route('admin.settings.taxes.rates.index') }}"></x-admin::datagrid.export>

            <!-- Create New Pages Button -->
            @if (bouncer()->hasPermission('settings.taxes.tax-rates.create'))
                <a 
                    href="{{ route('admin.settings.taxes.rates.create') }}"
                    class="primary-button"
                >
                    @lang('admin::app.settings.taxes.rates.index.button-title')
                </a>
            @endif
        </div>
    </div>
    
    <x-admin::datagrid 
        :src="route('admin.settings.taxes.rates.index')" 
        ref="datagrid"
    >
    </x-admin::datagrid>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-tax-rates-import-template">
            <div class="flex justify-between items-center">
                <div class="flex gap-x-2.5 items-center">
                    <div class="flex gap-x-2.5 items-center">
                        <!-- Import Button -->
                        <button
                            href="{{ route('admin.settings.taxes.rates.import') }}"
                            class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                            @click="$refs.importTaxRates.toggle()"
                        >
                            @lang('Import')
                        </button>
                    </div>
                </div>
            </div>

            <!-- Model Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form
                    @submit="handleSubmit($event, create)"
                    ref="importTaxForm"
                >
                    <x-admin::modal ref="importTaxRates">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p class="text-lg text-gray-800 dark:text-white font-bold">
                                @lang('admin::app.export.download')
                            </p>
                        </x-slot:header>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <x-admin::form action="">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.control
                                        type="file"
                                        id="file"
                                        name="file"
                                        ref="importFile"
                                    >
                                    </x-admin::form.control-group.control>
                                </x-admin::form.control-group>
                            </x-admin::form>
                        </x-slot:content>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <div class="flex gap-x-2.5 items-center">
                                <button
                                    type="submit"
                                    class="primary-button"
                                >
                                    @lang('admin::app.settings.taxes.categories.index.create.save-btn')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-tax-rates-import', {
                template: '#v-tax-rates-import-template',

                methods: {
                    create(params, { resetForm, setErrors }) {

                        const formData = new FormData();

                        const fileInput = this.$refs.importFile;

                        if (fileInput.files.length > 0) {
                            formData.append('file', fileInput.files[0]);
                        }

                        this.$axios.post("{{ route('admin.settings.taxes.rates.import') }}", formData)
                            .then((response) => {

                                this.$refs.importTaxRates.toggle();

                                this.$parent.$refs.datagrid.get();

                                resetForm();
                            })
                            .catch(error => {
                        });
1                   },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>