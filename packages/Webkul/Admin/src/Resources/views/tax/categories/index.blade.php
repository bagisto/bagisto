<x-admin::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('admin::app.settings.taxes.categories.index.title')
    </x-slot:title>

    <v-tax-categories>
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.settings.taxes.categories.index.title')
            </p>
            
            <div class="flex gap-x-[10px] items-center">
                <div class="flex gap-x-[10px] items-center">
                    {{-- Create Tax Category Button --}}
                    <button
                        type="button"
                        class="primary-button"
                    >
                        @lang('admin::app.settings.taxes.categories.index.create.title')
                    </button>
                </div>
            </div>
        </div>

        {{-- DataGrid Shimmer --}}
        <x-admin::shimmer.datagrid/>
    </v-tax-categories>
    
    @pushOnce('scripts')
        <script type="text/x-template" id="v-tax-categories-template">
            <div class="flex justify-between items-center">
                <p class="text-[20px] text-gray-800 font-bold">
                    @lang('admin::app.settings.taxes.categories.index.title')
                </p>
                
                <div class="flex gap-x-[10px] items-center">
                    <div class="flex gap-x-[10px] items-center">
                        <!-- Create Tax Category Button -->
                        @if (bouncer()->hasPermission('settings.taxes.categories.create'))
                            <button
                                type="button"
                                class="primary-button"
                                @click="id=0; $refs.taxCategory.toggle()"
                            >
                                @lang('admin::app.settings.taxes.categories.index.create.title')
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Datagrid -->
            <x-admin::datagrid
                :src="route('admin.settings.taxes.categories.index')"
                ref="datagrid"
            >
                <!-- DataGrid Header -->
                <template #header="{ columns, records, sortPage, applied}">
                    <div class="row grid grid-cols-4 grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] border-gray-300 text-gray-600 bg-gray-50 font-semibold">
                        <div
                            class="flex gap-[10px] cursor-pointer"
                            v-for="(columnGroup, index) in ['id', 'code', 'name']"
                        >
                            <p class="text-gray-600">
                                <span class="[&>*]:after:content-['_/_']">
                                    <span
                                        class="after:content-['/'] last:after:content-['']"
                                        :class="{
                                            'text-gray-800 font-medium': applied.sort.column == columnGroup,
                                            'cursor-pointer hover:text-gray-800': columns.find(columnTemp => columnTemp.index === columnGroup)?.sortable,
                                        }"
                                        @click="
                                            columns.find(columnTemp => columnTemp.index === columnGroup)?.sortable ? sortPage(columns.find(columnTemp => columnTemp.index === columnGroup)): {}
                                        "
                                    >
                                        @{{ columns.find(columnTemp => columnTemp.index === columnGroup)?.label }}
                                    </span>
                                </span>

                                <!-- Filter Arrow Icon -->
                                <i
                                    class="ml-[5px] text-[16px] text-gray-800 align-text-bottom"
                                    :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                    v-if="columnGroup.includes(applied.sort.column)"
                                ></i>
                            </p>
                        </div>

                        <!-- Actions -->
                        <p class="flex gap-[10px] justify-end">
                            @lang('admin::app.components.datagrid.table.actions')
                        </p>
                    </div>
                </template>

                <!-- DataGrid Body -->
                <template #body="{ columns, records }">
                    <div
                        v-for="record in records"
                        class="row grid gap-[10px] items-center px-[16px] py-[16px] border-b-[1px] border-gray-300 text-gray-600 transition-all hover:bg-gray-100"
                        style="grid-template-columns: repeat(4, 1fr);"
                    >
                        <!-- Id -->
                        <p v-text="record.id"></p>

                        <!-- Name -->
                        <p v-text="record.name"></p>

                        <!-- Code -->
                        <p v-text="record.code"></p>


                        <!-- Actions -->
                        <div class="flex justify-end">
                            <a @click="id=1; editModal(record.id)">
                                <span
                                    :class="record.actions['0'].icon"
                                    class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-100 max-sm:place-self-center"
                                    :title="record.actions['0'].title"
                                >
                                </span>
                            </a>

                            <a @click="deleteModal(record.actions['1']?.url)">
                                <span
                                    :class="record.actions['1'].icon"
                                    class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-100 max-sm:place-self-center"
                                    :title="record.actions['1'].title"
                                >
                                </span>
                            </a>
                        </div>
                    </div>
                </template>
            </x-admin::datagrid>


            <!-- Model Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form @submit="handleSubmit($event, updateOrCreate)">
                    <x-admin::modal ref="taxCategory">
                        <x-slot:header>
                            <p class="text-[18px] text-gray-800 font-bold">
                                <span v-if="id">
                                    @lang('admin::app.settings.taxes.categories.index.edit.title')
                                </span>

                                <span v-else>
                                    @lang('admin::app.settings.taxes.categories.index.create.title')
                                </span>
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                <!-- Code -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.taxes.categories.index.create.code')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="code"
                                        :value="old('code')"
                                        id="code"
                                        rules="required"
                                        :label="trans('admin::app.settings.taxes.categories.index.create.code')"
                                        :placeholder="trans('admin::app.settings.taxes.categories.index.create.code')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="code"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Name -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.taxes.categories.index.create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        :value="old('name')"
                                        id="name"
                                        rules="required"
                                        :label="trans('admin::app.settings.taxes.categories.index.create.name')"
                                        :placeholder="trans('admin::app.settings.taxes.categories.index.create.name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Description -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.taxes.categories.index.create.description')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="textarea"
                                        name="description"
                                        :value="old('description')"
                                        id="description"
                                        rules="required"
                                        :label="trans('admin::app.settings.taxes.categories.index.create.description')"
                                        :placeholder="trans('admin::app.settings.taxes.categories.index.create.description')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="description"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                @php 
                                    $selectedOptions = old('taxrates') ?: [] 
                                @endphp

                                <!-- Select Tax Rates -->
                                <p class="required block leading-[24px] text-gray-800 font-medium">
                                    @lang('admin::app.settings.taxes.categories.index.create.tax-rates')
                                </p>

                                @if(! count($taxRates))
                                    <div class="flex gap-[20px] items-center py-[10px]">
                                        <img 
                                            src="{{ bagisto_asset('images/tax.png') }}" 
                                            class="w-[80px] h-[80px] border border-dashed border-gray-300 rounded-[4px]"
                                                >
                                        <div class="flex flex-col gap-[6px]">
                                            <p class="text-[16px] text-gray-400 font-semibold">
                                                @lang('admin::app.settings.taxes.categories.index.create.add-tax-rates')
                                            </p>
                                            <p class="text-gray-400"> 
                                                @lang('admin::app.settings.taxes.categories.index.create.empty-text')
                                            </p>
                                        </div>

                                        <x-admin::form.control-group.control
                                            type="hidden"
                                            name="taxrates[]" 
                                            rules="required"
                                            :label="trans('admin::app.settings.taxes.categories.index.create.tax-rates')"
                                        >
                                        </x-admin::form.control-group.control>
                                    </div>
                                @else
                                    @foreach ($taxRates as $taxRate)
                                        <x-admin::form.control-group class="flex gap-[10px] !mb-0 p-[6px]">
                                            <x-admin::form.control-group.control
                                                type="checkbox"
                                                name="taxrates[]" 
                                                :value="$taxRate->id"
                                                :id="'taxrates_' . $taxRate->id"
                                                :for="'taxrates_' . $taxRate->id"
                                                rules="required"
                                                :label="trans('admin::app.settings.taxes.categories.index.create.tax-rates')"
                                                :checked="in_array($taxRate['id'], $selectedOptions)"
                                            >
                                            </x-admin::form.control-group.control>
                                                
                                            <x-admin::form.control-group.label 
                                                :for="'taxrates_' . $taxRate->id"
                                                class="!text-[14px] !text-gray-600 cursor-pointer"
                                            >
                                                {{ $taxRate['identifier'] }}
                                            </x-admin::form.control-group.label>
    
                                        </x-admin::form.control-group>
                                    @endforeach 
                                @endif
                                
                                <x-admin::form.control-group.error
                                    control-name="taxrates[]"
                                >
                                </x-admin::form.control-group.error>
                            </div>
                        </x-slot:content>

                        <x-slot:footer>
                            <div class="flex gap-x-[10px] items-center">
                                <!-- Save Button -->
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
            app.component('v-tax-categories', {
                template: '#v-tax-categories-template',

                data() {
                    return {
                        id: 0,
                    }
                },

                methods: {
                    updateOrCreate(params, { resetForm, setErrors }) {
                        this.$axios.post(params.id ? "{{ route('admin.settings.taxes.categories.update') }}" : "{{ route('admin.settings.taxes.categories.store') }}", params,{
                        headers: {
                            'Content-Type': 'multipart/form-data'
                            }
                        })
                            .then((response) => {
                                this.$refs.taxCategory.toggle();

                                this.$refs.datagrid.get();
                                
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                                resetForm();
                            })
                            .catch((error) =>{
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    editModal(id) {
                        this.$axios.get(`{{ route('admin.settings.taxes.categories.edit', '') }}/${id}`)
                            .then((response) => {
                                let values = {
                                    id: response.data.data.id,
                                    name: response.data.data.name,
                                    code: response.data.data.code,
                                    description: response.data.data.description,
                                };

                                this.$refs.taxCategory.toggle();

                                this.$refs.modalForm.setValues(values);
                            })
                            .catch(error => {
                                if (error.response.status ==422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    deleteModal(url) {
                        if (! confirm('Are you sure, you want to perform this action?')) {
                            return;
                        }

                        this.$axios.post(url, {
                            '_method': 'DELETE'
                        })
                            .then((response) => {
                                this.$refs.datagrid.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                            })
                            .catch(error => {
                                if (error.response.status ==422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    }

                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>