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
            <v-tax-categories>
                <button
                    href="{{ route('admin.dashboard.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                >
                    @lang('import')
                </button>
            </v-tax-categories>

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
    
    <x-admin::datagrid :src="route('admin.settings.taxes.rates.index')"></x-admin::datagrid>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-tax-categories-template">
            <div class="flex justify-between items-center">
                <div class="flex gap-x-2.5 items-center">
                    <div class="flex gap-x-2.5 items-center">
                        <!-- Import Button -->
                        <button
                            {{-- href="{{ route('admin.dashboard.index') }}" --}}
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
                ref="modalForm"
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
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-tax-categories', {
                template: '#v-tax-categories-template',

                data() {
                    return {
                    
                    }
                },

                methods: {
                  
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>