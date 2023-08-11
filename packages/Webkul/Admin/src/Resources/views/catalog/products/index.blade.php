<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.products.index.title')
    </x-slot:title>

    <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.catalog.products.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            <!-- Dropdown -->
            <x-admin::dropdown position="bottom-right">
                <x-slot:toggle>
                    <span class="icon-setting p-[6px] rounded-[6px] text-[24px]  cursor-pointer transition-all hover:bg-gray-100"></span>
                </x-slot:toggle>

                <x-slot:content class="w-[174px] max-w-full !p-[8PX] border border-gray-300 rounded-[4px] z-10 bg-white shadow-[0px_8px_10px_0px_rgba(0,_0,_0,_0.2)]">
                    <div class="grid gap-[2px]">
                        <!-- Current Channel -->
                        <div class="p-[6px] items-center cursor-pointer transition-all hover:bg-gray-100 hover:rounded-[6px]">
                            <p class="text-gray-600 font-semibold leading-[24px]">
                                Channel - {{ core()->getCurrentChannel()->name }}
                            </p>
                        </div>

                        <!-- Current Locale -->
                        <div class="p-[6px] items-center cursor-pointer transition-all hover:bg-gray-100 hover:rounded-[6px]">
                            <p class="text-gray-600 font-semibold leading-[24px]">
                                Language - {{ core()->getCurrentLocale()->name }}
                            </p>
                        </div>

                        <div class="p-[6px] items-center cursor-pointer transition-all hover:bg-gray-100 hover:rounded-[6px]">
                            <!-- Export Modal -->
                            <x-admin::modal ref="exportModal">
                                <x-slot:toggle>
                                    <p class="text-gray-600 font-semibold leading-[24px]">
                                        Export                                            
                                    </p>
                                </x-slot:toggle>

                                <x-slot:header>
                                    <p class="text-[18px] text-gray-800 font-bold">
                                        @lang('Download')
                                    </p>
                                </x-slot:header>

                                <x-slot:content>
                                    <div class="p-[16px]">
                                        <x-admin::form action="">
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.control
                                                    type="select"
                                                    name="format"
                                                    id="format"
                                                >
                                                    <option value="xls">XLS</option>
                                                    <option value="csv">CLS</option>
                                                </x-admin::form.control-group.control>
                                            </x-admin::form.control-group>
                                        </x-admin::form>
                                    </div>
                                </x-slot:content>
                                <x-slot:footer>
                                    <!-- Save Button -->
                                    <button
                                        type="submit" 
                                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                    >
                                        @lang('Export')
                                    </button>
                                </x-slot:footer>
                            </x-admin::modal>
                        </div>
                    </div>
                </x-slot:content>
            </x-admin::dropdown>

            {!! view_render_event('bagisto.admin.catalog.products.create.before') !!}
    
            @if (bouncer()->hasPermission('catalog.products.create'))
                <v-create-product-form></v-create-product-form>
            @endif
    
            {!! view_render_event('bagisto.admin.catalog.products.create.after') !!}
        </div>
    </div>
    
    {!! view_render_event('bagisto.admin.catalog.products.list.before') !!}

    <x-admin::datagrid src="{{ route('admin.catalog.products.index') }}"></x-admin::datagrid>

    {!! view_render_event('bagisto.admin.catalog.products.list.after') !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-create-product-form-template">
            <!-- Product Create Button -->
            @if (bouncer()->hasPermission('catalog.products.create'))
                <button 
                    type="button"
                    class="text-gray-50 font-semibold px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] cursor-pointer"
                    @click="$refs.productCreateModal.toggle()"
                >
                    @lang('admin::app.catalog.products.index.create-btn')
                </button>
            @endif

            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, create)">
                    <!-- Customer Create Modal -->
                    <x-admin::modal ref="productCreateModal">
                        <x-slot:header>
                            <!-- Modal Header -->
                            <p
                                class="text-[18px] text-gray-800 font-bold"
                                v-if="! attributes.length"
                            >
                                @lang('admin::app.catalog.products.index.create.title')
                            </p>    

                            <p
                                class="text-[18px] text-gray-800 font-bold"
                                v-else
                            >
                                @lang('admin::app.catalog.products.index.create.configurable-attributes')
                            </p>    
                        </x-slot:header>
        
                        <x-slot:content>
                            <!-- Modal Content -->
                            <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                <div v-show="! attributes.length">
                                    {!! view_render_event('bagisto.admin.catalog.products.create_form.general.controls.before') !!}

                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.catalog.products.index.create.type')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="type"
                                            rules="required"
                                            :label="trans('admin::app.catalog.products.index.create.type')"
                                        >
                                            @foreach(config('product_types') as $key => $type)
                                                <option value="{{ $key }}">
                                                    {{ __('admin::app.catalog.products.index.create.' . $key) }}
                                                </option>
                                            @endforeach
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error control-name="type"></x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.catalog.products.index.create.family')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="attribute_family_id"
                                            rules="required"
                                            :label="trans('admin::app.catalog.products.index.create.family')"
                                        >
                                            @foreach($families as $family)
                                                <option value="{{ $family->id }}">
                                                    {{ $family->name }}
                                                </option>
                                            @endforeach
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error control-name="attribute_family_id"></x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.catalog.products.index.create.sku')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="sku"
                                            ::rules="{ required: true, regex: /^[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*$/ }"
                                            :label="trans('admin::app.catalog.products.index.create.sku')"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error control-name="sku"></x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    {!! view_render_event('bagisto.admin.catalog.products.create_form.general.controls.before') !!}
                                </div>

                                <div v-show="attributes.length">
                                    {!! view_render_event('bagisto.admin.catalog.products.create_form.attributes.controls.before') !!}

                                    <div
                                        class="mb-[10px]"
                                        v-for="attribute in attributes"
                                    >
                                        <label class="block leading-[24px] text-[12px] text-gray-800 font-medium">
                                            @{{ attribute.name }}
                                        </label>

                                        <div class="flex gap-[4px] min-h-[38px] p-[6px] border border-gray-300 rounded-[6px]">
                                            <p
                                                class="flex items-center py-[3px] px-[8px] bg-gray-600 rounded-[3px] text-white font-semibold"
                                                v-for="option in attribute.options"
                                            >
                                                @{{ option.name + option.id }}

                                                <span
                                                    class="icon-cross text-white text-[18px] ml-[5px] cursor-pointer"
                                                    @click="removeOption(option)"
                                                ></span>
                                            </p>
                                        </div>
                                    </div>

                                    {!! view_render_event('bagisto.admin.catalog.products.create_form.attributes.controls.before') !!}
                                </div>
                            </div>
                        </x-slot:content>
        
                        <x-slot:footer>
                            <!-- Modal Submission -->
                            <div class="flex gap-x-[10px] items-center">
                                <button
                                    type="button"
                                    class="text-gray-600 font-semibold whitespace-nowrap px-[12px] py-[6px] border-[2px] border-transparent rounded-[6px] transition-all hover:bg-gray-100 cursor-pointer"
                                    v-if="attributes.length"
                                    @click="attributes = []"
                                >
                                    @lang('admin::app.catalog.products.index.create.back-btn')
                                </button>

                                <button 
                                    type="submit"
                                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                >
                                    @lang('admin::app.catalog.products.index.create.save-btn')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-create-product-form', {
                template: '#v-create-product-form-template',

                data() {
                    return {
                        attributes: [],

                        superAttributes: {}
                    };
                },

                methods: {
                    create(params, { resetForm, resetField, setErrors }) {
                        this.attributes.forEach(attribute => {
                            params.super_attributes ||= {};

                            params.super_attributes[attribute.code] = this.superAttributes[attribute.code];
                        });

                        this.$axios.post("{{ route('admin.catalog.products.store') }}", params)
                            .then((response) => {
                                if (response.data.data.redirect_url) {
                                    window.location.href = response.data.data.redirect_url;
                                } else {
                                    this.attributes = response.data.data.attributes;

                                    this.setSuperAttributes();
                                }
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    removeOption(option) {
                        this.attributes.forEach(attribute => {
                            attribute.options = attribute.options.filter(item => item.id != option.id);
                        });

                        this.attributes = this.attributes.filter(attribute => attribute.options.length > 0);

                        this.setSuperAttributes();
                    },

                    setSuperAttributes() {
                        this.superAttributes = {};

                        this.attributes.forEach(attribute => {
                            this.superAttributes[attribute.code] = [];

                            attribute.options.forEach(option => {
                                this.superAttributes[attribute.code].push(option.id);
                            });
                        });
                    }
                }
            })
        </script>
    @endPushOnce

</x-admin::layouts>