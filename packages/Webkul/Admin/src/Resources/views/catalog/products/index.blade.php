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
                <v-create-product-form>
                    <button 
                        type="button"
                        class="text-gray-50 font-semibold px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] cursor-pointer"
                    >
                        @lang('admin::app.catalog.products.index.create-btn')
                    </button>
                </v-create-product-form>
            @endif
    
            {!! view_render_event('bagisto.admin.catalog.products.create.after') !!}
        </div>
    </div>
    
    {!! view_render_event('bagisto.admin.catalog.products.list.before') !!}

    <x-admin::datagrid src="{{ route('admin.catalog.products.index') }}">
        <template #header="{ columns, records, sortPage }">
            <div class="row grid px-[16px] py-[10px] border-b-[1px] border-gray-300 grid-cols-3 grid-rows-1">
                <div class="cursor-pointer">
                    <div class="flex gap-[10px]">
                        <span class="icon-uncheckbox text-[24px]"></span>
                        <p class="text-gray-600">
                            <span @click="sortPage(columns.find(column => column.index === 'product_name'))">
                                @lang('admin::app.catalog.products.index.product_name')
                            </span> /

                            <span @click="sortPage(columns.find(column => column.index === 'product_sku'))">
                                @lang('admin::app.catalog.products.index.sku')
                            </span> /

                            <span @click="sortPage(columns.find(column => column.index === 'product_number'))">
                                @lang('admin::app.catalog.products.index.product_number')
                            </span>
                        </p>
                    </div>
                </div>

                <div class="cursor-pointer">
                    <p class="text-gray-600">
                        @lang('admin::app.catalog.products.index.image')/
                        @lang('admin::app.catalog.products.index.price')/
                        @lang('admin::app.catalog.products.index.stock')/
                        @lang('admin::app.catalog.products.index.id')
                    </p>
                </div>

                <div class="cursor-pointer">
                    <p class="text-gray-600">
                        <span @click="sortPage(columns.find(column => column.index === 'status'))">
                            @lang('admin::app.catalog.products.index.status')
                        </span> / 
                        
                        @lang('admin::app.catalog.products.index.category') /

                        <span @click="sortPage(columns.find(column => column.index === 'product_type'))">
                            @lang('admin::app.catalog.products.index.type')
                        </span>
                    </p>
                </div>
            </div>
        </template>

        <template #body="{ columns, records }">
            <div
                class="row grid grid-cols-3 grid-rows-1 px-[16px] py-[10px] border-b-[1px] border-gray-300"
                v-for="record in records"
            >
                {{-- Product Name, SKU, Product Number --}}
                <div class="">
                    <div class="flex gap-[10px]">
                        <span class="icon-uncheckbox text-[24px]"></span>

                        <div class="flex flex-col gap-[6px]">
                            <p
                                class="text-[16px] text-gray-800 font-semibold"
                                v-text="record.product_name"
                            >
                            </p>

                            <p
                                class="text-gray-600"
                            >
                                @lang('admin::app.catalog.products.index.sku') - @{{ record.product_sku }}
                            </p>

                            <p
                                class="text-gray-600"
                            >
                                @lang('admin::app.catalog.products.index.number') - @{{ record.product_number }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Image, Price, Id, Stock --}}
                <div class="">
                    <div class="flex gap-[6px]">
                        <div class="relative">
                            <img
                                class="min-h-[65px] min-w-[65px] max-h-[65px] max-w-[65px] rounded-[4px]"
                                v-if="record.path"
                                :src=`{{ Storage::url('') }}${record.path}`
                            />

                            <img 
                                class="min-h-[65px] min-w-[65px] max-h-[65px] max-w-[65px] rounded-[4px]"
                                v-else
                                src="{{ bagisto_asset('images/product-placeholders/front.svg') }}"
                            />

                            <span
                                class="absolute bottom-[1px] left-[1px] text-[12px] font-bold text-white bg-darkPink rounded-full px-[6px]"
                                v-text="record.quantity ?? 0"
                            >
                            </span>
                        </div>

                        <div class="flex flex-col gap-[6px]">
                            <p 
                                class="text-[16px] text-gray-800 font-semibold"
                                v-text="record.price"
                            >
                            </p>

                            <p class="text-gray-600" v-if="record.quantity > 0">
                                <a href="#" class="text-green-600">
                                    @{{ record.quantity }} @lang('admin::app.catalog.products.index.available')
                                </a>
                            </p>

                            <p class="text-gray-600" v-else>
                                <a href="#" class="text-red-600">
                                    @lang('admin::app.catalog.products.index.out-of-stock')
                                </a>
                            </p>
    
                            <p
                                class="text-gray-600"
                            >
                            @lang('admin::app.catalog.products.index.id') - @{{ record.product_id }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Status, Category, Type --}}
                <div class="flex gap-x-[16px] justify-between items-center">
                    <div class="flex flex-col gap-[6px]">
                        <p
                            :class="{
                                'label-cancelled': record.status == '',
                                'label-active': record.status === 1,
                            }"
                        >
                            @{{ record.status ? 'Active' : 'Disable' }}
                        </p>

                        <p
                            class="text-gray-600"
                            v-text="record.category_name"
                        >
                        </p>

                        <p
                            class="text-gray-600"
                            v-text="record.product_type"
                        >
                        </p>
                    </div>

                    <a :href=`{{ route('admin.catalog.products.edit', '') }}/${record.product_id}`>
                        <span class="icon-sort-right text-[24px] ml-[4px]p-[6px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-100"></span>
                    </a>

                </div>
            </div>
        </template>
    </x-admin::datagrid>

    {!! view_render_event('bagisto.admin.catalog.products.list.after') !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-create-product-form-template">
            <div>
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
                                                        @lang('admin::app.catalog.products.index.create.' . $key)
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
            </div>
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