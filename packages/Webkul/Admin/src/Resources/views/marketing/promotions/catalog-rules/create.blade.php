<x-admin::layouts>    
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.promotions.catalog-rules.add-title')
    </x-slot:title>

    {{-- Create Catalog form --}}
    <v-catalog-rule-create-form></v-catalog-rule-create-form>

    @pushOnce('scripts')
        {{-- v catalog rule create form template --}}
        <script type="text/x-template" id="v-catalog-rule-create-form-template">
            <div>
                <x-admin::form 
                    :action="route('admin.catalog_rules.store')"
                    enctype="multipart/form-data"
                >
                    <div class="grid">
                        <div class="flex  justify-between items-center cursor-pointer">
                            <div>
                                <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 text-center w-full max-w-max rounded-[6px]">
                                    <span class="icon-sort-left text-[24px]"></span>

                                    <p class="text-gray-600">
                                        @lang('Cart Marketing')
                                    </p>
                                </div>
                            </div>
                            
                            <button 
                                type="submit"
                                class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                            >
                                @lang('admin::app.promotions.catalog-rules.save-btn-title')
                            </button>
                        </div>
                    </div>
        
                    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                        <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                            <div class="p-[16px] bg-white rounded-[4px] box-shadow">

                                <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                                    General
                                </p>

                                <div class="mb-[10px]">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.inventory-sources.create.contact-name')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="contact_name"
                                            :value="old('contact_name')"
                                            id="contact_name"
                                            rules="required"
                                            :label="trans('admin::app.settings.inventory-sources.create.contact-name')"
                                            :placeholder="trans('admin::app.settings.inventory-sources.create.contact-name')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="contact_name"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>

                                <div class="mb-[10px]">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            Description
                                        </x-admin::form.control-group.label>
    
                                        <x-admin::form.control-group.control
                                            type="textarea"
                                            name="description"
                                            :value="old('description')"
                                            id="description"
                                            :label="trans('Description')"
                                            :placeholder="trans('Description')"
                                        >
                                        </x-admin::form.control-group.control>
    
                                        <x-admin::form.control-group.error
                                            control-name="description"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>

                                <div class="mb-[10px]">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            Channels
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="channels[]"
                                            :value="old('channels[]')"
                                            id="channels[]"
                                            rules="required"
                                            :label="trans('Channels')"
                                            :placeholder="trans('Channels')"
                                            multiple
                                        >
                                            @foreach(core()->getAllChannels() as $channel)
                                                <option value="{{ $channel->id }}" {{ old('channels') && in_array($channel->id, old('channels')) ? 'selected' : '' }}>
                                                    {{ core()->getChannelName($channel) }}
                                                </option>
                                            @endforeach
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="channels[]"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>

                                <div class="mb-[10px]">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            Customer Group
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="customer_groups"
                                            :value="old('customer_groups')"
                                            id="customer_groups"
                                            rules="required"
                                            :label="trans('Customer Groups')"
                                            :placeholder="trans('Customer Groups')"
                                            multiple
                                        >
                                            @foreach(app('Webkul\Customer\Repositories\CustomerGroupRepository')->all() as $customerGroup)
                                                <option 
                                                    value="{{ $customerGroup->id }}" 
                                                    {{ old('customer_groups') && in_array($customerGroup->id, old('customer_groups')) ? 'selected' : '' }}
                                                >
                                                    {{ $customerGroup->name }}
                                                </option>
                                            @endforeach
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="customer_groups"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>

                            </div>
        
                            <!-- Condition -->
                            <div class="p-[16px] bg-white rounded-[4px] box-shadow">
        
                                <div class="flex gap-[16px] items-center justify-between">
                                    <p class="text-[16px] text-gray-800 font-semibold">
                                        @lang('Conditions')    
                                    </p>

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.promotions.catalog-rules.condition-type')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="condition_type"
                                            :value="old('condition_type')"
                                            id="condition_type"
                                            class="pr-[40px] text-gray-400 border-gray-300"
                                            :label="trans('Customer Groups')"
                                            :placeholder="trans('Customer Groups')"
                                            v-model="condition_type"
                                        >
                                            <option value="1">{{ __('admin::app.promotions.catalog-rules.all-conditions-true') }}</option>
                                            <option value="2">{{ __('admin::app.promotions.catalog-rules.any-condition-true') }}</option>
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="condition_type"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>
        
                                <v-catalog-rule-condition-item
                                    v-for='(condition, index) in conditions'
                                    :condition="condition"
                                    :key="index"
                                    :index="index"
                                    @onRemoveCondition="removeCondition($event)"
                                >
                                </v-catalog-rule-condition-item>
                      
                                <div 
                                    class="max-w-max px-[12px] py-[5px] mt-[15px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                    @click="addCondition"
                                >
                                    @lang('Add Condition')
                                </div>
        
                            </div>
        
                            <!-- Action -->
                            <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                                <div class="grid gap-[6px]">
                                    <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">Actions</p>
        
                                    <div class="flex  gap-[16px]  max-sm:flex-wrap">
                                        <div class="w-full mb-[10px]">
                                            <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username"> Discount Type* </label>
                                            <input class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400" type="text" placeholder="Percentage of Product Price">
                                        </div>
                                        <div class="w-full mb-[10px]">
                                            <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username"> Discount Type* </label>
                                            <input class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400" type="text" placeholder="Percentage of Product Price">
                                        </div>
                                    </div>
                                    <div class="flex  gap-[16px]  max-sm:flex-wrap">
                                        <div class="w-full mb-[10px]">
                                            <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username"> Maximum quantity allowed to be discounted*
                                            </label>
                                            <input class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400" type="text" placeholder="0">
                                        </div>
                                        <div class="w-full mb-[10px]">
                                            <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username"> Buy X Quantity*
                                            </label>
                                            <input class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400" type="text" placeholder="0">
                                        </div>
                                    </div>
        
                                    <div class="flex  gap-[16px]  max-sm:flex-wrap">
                                        <div class="w-full mb-[10px]">
                                            <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username"> Apply to shipping* </label>
                                            <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 text-[14px] font-normal py-[6px] px-[12px] text-center w-full bg-white border border-gray-300 rounded-[6px] cursor-pointertransition-all hover:border-gray-400">
                                                No<span class="icon-sort-down text-[24px]"></span>
                                            </div>
                                        </div>
                                        <div class="w-full mb-[10px]">
                                            <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username"> Free Shipping* </label>
                                            <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 text-[14px] font-normal py-[6px] px-[12px] text-center w-full bg-white border border-gray-300 rounded-[6px] cursor-pointertransition-all hover:border-gray-400">
                                                6<span class="icon-sort-down text-[24px]"></span>
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="flex  gap-[16px] justify-between max-sm:flex-wrap">
                                        <div class="w-full mb-[10px]">
                                            <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username"> Apply to shipping*
                                            </label>
                                            <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 text-[14px] font-normal py-[6px] px-[12px] text-center w-1/2 bg-white border border-gray-300 rounded-[6px] cursor-pointertransition-all hover:border-gray-400 max-sm:w-full">
                                                No<span class="icon-sort-down text-[24px]"></span>
                                            </div>
                                        </div>
                                    </div>
        
                                </div>
                            </div>
        
        
                        </div>
                        <!-- Right sub-component -->
                        <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                            <!-- component 1 -->
                            <div class="bg-white rounded-[4px] box-shadow">
                                <div class="flex items-center justify-between p-[6px]">
                                    <p class="text-gray-600 text-[16px] p-[10px] font-semibold">Settings</p>
                                    <span class="icon-arrow-up text-[24px] p-[6px]  rounded-[6px] cursor-pointer transition-all hover:bg-gray-100"></span>
                                </div>
                                <div class="px-[16px] pb-[16px]">

                                    <label
                                        for="checkbox"
                                        class="flex gap-[10px] w-max p-[6px] items-center cursor-pointer select-none"
                                    >
                                        <input
                                            type="checkbox"
                                            class="hidden peer"
                                            id="checkbox"
                                            name="status"
                                            value="1"
                                            required
                                        >

                                        <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:text-blue-600 peer-checked:icon-checked peer-checked:text-navyBlue"></span>

                                        <div
                                            {{-- for="checkbox" --}}
                                            class="text-[14px] text-gray-600 font-semibold cursor-pointer"
                                        >
                                            @lang('Status')
                                        </div>

                                    </label>

                                    <div class="mb-[10px]">
                                        <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                Priority
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="sort_order"
                                                :value="old('sort_order')"
                                                id="sort_order"
                                                :label="trans('Priority')"
                                                :placeholder="trans('Priority')"
                                            >
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error
                                                control-name="sort_order"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>
                                    <div class="">
                                        <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username"> Uses Per Customer*
                                        </label>
                                        <input class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400" type="text" placeholder="0">
                                    </div>
                                </div>
                            </div>
        
                            <!-- component 2 -->
                            <div class="bg-white rounded-[4px] box-shadow">
                                <div class="flex items-center justify-between p-[6px]">
                                    <p class="text-gray-600 text-[16px] p-[10px] font-semibold">Marketing Time</p>
                                    <span class="icon-arrow-up text-[24px] p-[6px]  rounded-[6px] cursor-pointer transition-all hover:bg-gray-100"></span>
                                </div>
        
                                <div class="px-[16px] pb-[16px]">
                                    <div class="flex gap-[16px]">
                                        <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                From
                                            </x-admin::form.control-group.label>
        
                                            <x-admin::form.control-group.control
                                                type="date"
                                                name="starts_from"
                                                :value="old('starts_from')"
                                                id="starts_from"
                                                rules="required"
                                                :label="trans('From')"
                                                :placeholder="trans('From')"
                                            >
                                            </x-admin::form.control-group.control>
        
                                            <x-admin::form.control-group.error
                                                control-name="starts_from"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>

                                        <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                To
                                            </x-admin::form.control-group.label>
        
                                            <x-admin::form.control-group.control
                                                type="date"
                                                name="ends_till"
                                                :value="old('ends_till')"
                                                id="ends_till"
                                                rules="required"
                                                :label="trans('ends_till')"
                                                :placeholder="trans('ends_till')"
                                            >
                                            </x-admin::form.control-group.control>
        
                                            <x-admin::form.control-group.error
                                                control-name="ends_till"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>
                                </div>
        
                            </div>
                        </div>
                    </div>
                </x-admin::form>
            </div>
        </script>

        {{-- v catalog rule create form component --}}
        <script type="module">
            app.component('v-catalog-rule-create-form', {
                template: '#v-catalog-rule-create-form-template',
                
                data() {
                    return {
                        condition_type: 1,

                        conditions: []
                    }
                },

                methods: {
                    addCondition() {
                        this.conditions.push({
                            'attribute': '',
                            'operator': '==',
                            'value': '',
                        });
                    },

                    removeCondition(condition) {
                        let index = this.conditions.indexOf(condition)

                        this.conditions.splice(index, 1)
                    },

                    onSubmit(e) {
                        this.$root.onSubmit(e)
                    },

                    onSubmit(e) {
                        this.$root.onSubmit(e)
                    },

                    redirectBack(fallbackUrl) {
                        this.$root.redirectBack(fallbackUrl)
                    }
                }
            })
        </script>
   
        {{-- v catalog rule condition item template --}}
        <script type="text/x-template" id="v-catalog-rule-condition-item-template">
            <div class="flex gap-[16px] justify-between mt-[15px]">
                <div class="flex gap-[16px] flex-1 max-sm:flex-wrap max-sm:flex-1">
                    <select
                        :name="['conditions[' + index + '][attribute]']"
                        :id="['conditions[' + index + '][attribute]']"
                        class="inline-flex gap-x-[4px] justify-between items-center max-h-[40px] max-w-[196px] py-[6px] pl-[12px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400"
                        v-model="condition.attribute"
                    >
                        <option value="">@lang('admin::app.promotions.catalog-rules.choose-condition-to-add')</option>

                        <optgroup 
                            v-for='(conditionAttribute, index) in conditionAttributes'
                            :label="conditionAttribute.label"
                        >
                            <option 
                                v-for='(childAttribute, index) in conditionAttribute.children'
                                :value="childAttribute.key"
                                :text="childAttribute.label"
                            >
                            </option>
                        </optgroup>
                    </select>

                    <select 
                        :name="['conditions[' + index + '][operator]']"
                        class="inline-flex gap-x-[4px] justify-between items-center max-h-[40px] max-w-[196px] py-[6px] pl-[12px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400"
                        v-model="condition.operator"
                        v-if="matchedAttribute"
                    >
                        <option 
                            v-for='operator in conditionOperators[matchedAttribute.type]'
                            :value="operator.operator"
                            :text="operator.label"
                        >
                        </option>
                    </select>

                    <div v-if="matchedAttribute">
                        <input 
                            type="hidden"
                            :name="['conditions[' + index + '][attribute_type]']"
                            v-model="matchedAttribute.type"
                        >

                        <div v-if="matchedAttribute.key == 'product|category_ids'">
                            <v-tree-view 
                                value-field="id"
                                id-field="id"
                                :name-field="'conditions[' + index + '][value]'"
                                input-type="checkbox"
                                :items='matchedAttribute.options'
                                :behavior="'no'"
                                fallback-locale="{{ config('app.fallback_locale') }}"
                            >
                            </v-tree-view>
                        </div>

                        <div v-else>
                            <div 
                                class="control-group"
                                v-if="matchedAttribute.type == 'text' || matchedAttribute.type == 'price' || matchedAttribute.type == 'decimal' || matchedAttribute.type == 'integer'"
                            >
                                <input 
                                    v-model="condition.value"
                                    data-vv-as="&quot;{{ __('admin::app.promotions.catalog-rules.conditions') }}&quot;"
                                />
                            </div>

                            <div
                                class="control-group date"
                                v-if="matchedAttribute.type == 'date'"
                            >
                                <input 
                                    type="date"
                                    :name="['conditions[' + index + '][value]']"
                                    v-model="condition.value"
                                />
                            </div>

                            <div class="control-group date" v-if="matchedAttribute.type == 'datetime'">
                                <input 
                                    type="datetime"
                                    :name="['conditions[' + index + '][value]']"
                                    v-model="condition.value"
                                />
                            </div>

                            <div
                                class="control-group" 
                                v-if="matchedAttribute.type == 'boolean'"
                            >
                                <select 
                                    :name="['conditions[' + index + '][value]']"
                                    v-model="condition.value"
                                >
                                    <option value="1">@lang('admin::app.promotions.catalog-rules.yes')</option>
                                    <option value="0">@lang('admin::app.promotions.catalog-rules.no')</option>
                                </select>
                            </div>

                            <div 
                                class="control-group"
                                v-if="matchedAttribute.type == 'select' || matchedAttribute.type == 'radio'"
                            >
                                <select
                                    :name="['conditions[' + index + '][value]']"
                                    v-model="condition.value"
                                    v-if="matchedAttribute.key != 'catalog|state'"
                                >
                                    <option
                                        v-for='option in matchedAttribute.options'
                                        :value="option.id"
                                        :text="option.admin_name"
                                    >
                                    </option>
                                </select>

                                <select 
                                    :name="['conditions[' + index + '][value]']"
                                    
                                    v-model="condition.value" 
                                    v-else
                                >
                                    <optgroup
                                        v-for='option in matchedAttribute.options'
                                        :label="option.admin_name"
                                    >
                                        <option
                                            v-for='state in option.states'
                                            :value="state.code"
                                            :text="state.admin_name"
                                        >
                                        </option>
                                    </optgroup>
                                </select>
                            </div>

                            <div 
                                class="control-group multi-select"
                                v-if="matchedAttribute.type == 'multiselect' || matchedAttribute.type == 'checkbox'"
                            >
                                <select 
                                    :name="['conditions[' + index + '][value][]']"
                                    v-model="condition.value" multiple
                                >
                                    <option
                                        v-for='option in matchedAttribute.options'
                                        :value="option.id"
                                        :text="option.admin_name"
                                    >
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <span 
                    class="icon-delete text-[24px] p-[6px]  rounded-[6px] cursor-pointer transition-all hover:bg-gray-100 max-sm:place-self-center"
                    @click="removeCondition"
                >
                </span>
            </div>
        </script>

        {{-- v catalog rule condition item component --}}
        <script type="module">
            app.component('v-catalog-rule-condition-item', {
                template: "#v-catalog-rule-condition-item-template",

                props: ['index', 'condition'],

                data() {
                    return {
                        conditionAttributes: @json(app('\Webkul\CatalogRule\Repositories\CatalogRuleRepository')->getConditionAttributes()),

                        attributeTypeIndexes: {
                            'product': 0
                        },

                        conditionOperators: {
                            'price': [{
                                    'operator': '==',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.is-equal-to') }}'
                                }, {
                                    'operator': '!=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.is-not-equal-to') }}'
                                }, {
                                    'operator': '>=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.equals-or-greater-than') }}'
                                }, {
                                    'operator': '<=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.equals-or-less-than') }}'
                                }, {
                                    'operator': '<=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.greater-than') }}'
                                }, {
                                    'operator': '<=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.less-than') }}'
                                }],
                            'decimal': [{
                                    'operator': '==',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.is-equal-to') }}'
                                }, {
                                    'operator': '!=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.is-not-equal-to') }}'
                                }, {
                                    'operator': '>=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.equals-or-greater-than') }}'
                                }, {
                                    'operator': '<=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.equals-or-less-than') }}'
                                }, {
                                    'operator': '>',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.greater-than') }}'
                                }, {
                                    'operator': '<',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.less-than') }}'
                                }],
                            'integer': [{
                                    'operator': '==',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.is-equal-to') }}'
                                }, {
                                    'operator': '!=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.is-not-equal-to') }}'
                                }, {
                                    'operator': '>=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.equals-or-greater-than') }}'
                                }, {
                                    'operator': '<=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.equals-or-less-than') }}'
                                }, {
                                    'operator': '>',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.greater-than') }}'
                                }, {
                                    'operator': '<',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.less-than') }}'
                                }],
                            'text': [{
                                    'operator': '==',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.is-equal-to') }}'
                                }, {
                                    'operator': '!=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.is-not-equal-to') }}'
                                }, {
                                    'operator': '{}',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.contain') }}'
                                }, {
                                    'operator': '!{}',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.does-not-contain') }}'
                                }],
                            'boolean': [{
                                    'operator': '==',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.is-equal-to') }}'
                                }, {
                                    'operator': '!=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.is-not-equal-to') }}'
                                }],
                            'date': [{
                                    'operator': '==',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.is-equal-to') }}'
                                }, {
                                    'operator': '!=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.is-not-equal-to') }}'
                                }, {
                                    'operator': '>=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.equals-or-greater-than') }}'
                                }, {
                                    'operator': '<=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.equals-or-less-than') }}'
                                }, {
                                    'operator': '>',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.greater-than') }}'
                                }, {
                                    'operator': '<',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.less-than') }}'
                                }],
                            'datetime': [{
                                    'operator': '==',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.is-equal-to') }}'
                                }, {
                                    'operator': '!=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.is-not-equal-to') }}'
                                }, {
                                    'operator': '>=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.equals-or-greater-than') }}'
                                }, {
                                    'operator': '<=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.equals-or-less-than') }}'
                                }, {
                                    'operator': '>',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.greater-than') }}'
                                }, {
                                    'operator': '<',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.less-than') }}'
                                }],
                            'select': [{
                                    'operator': '==',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.is-equal-to') }}'
                                }, {
                                    'operator': '!=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.is-not-equal-to') }}'
                                }],
                            'radio': [{
                                    'operator': '==',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.is-equal-to') }}'
                                }, {
                                    'operator': '!=',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.is-not-equal-to') }}'
                                }],
                            'multiselect': [{
                                    'operator': '{}',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.contains') }}'
                                }, {
                                    'operator': '!{}',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.does-not-contain') }}'
                                }],
                            'checkbox': [{
                                    'operator': '{}',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.contains') }}'
                                }, {
                                    'operator': '!{}',
                                    'label': '{{ __('admin::app.promotions.catalog-rules.does-not-contain') }}'
                                }]
                        }
                    }
                },

                computed: {
                    matchedAttribute() {
                        if (this.condition.attribute == '')
                            return;


                        let attributeIndex = this.attributeTypeIndexes[this.condition.attribute.split("|")[0]];

                        let matchedAttribute = this.conditionAttributes[attributeIndex]['children'].filter((attribute) => {
                            return attribute.key == this.condition.attribute;
                        });

                        if (matchedAttribute[0]['type'] == 'multiselect' || matchedAttribute[0]['type'] == 'checkbox') {
                            this.condition.operator = '{}';

                            this.condition.value = [];
                        }

                        return matchedAttribute[0];
                    }
                },

                methods: {
                    removeCondition() {
                        this.$emit('onRemoveCondition', this.condition)
                    },
                }
            });
        </script>

        {{-- v-tree-view component --}}
        <script type="module">
            app.component('v-tree-view',{
                name: 'v-tree-view',

                inheritAttrs: false,

                props: {
                    inputType: {
                        type: String,
                        required: false,
                        default: 'checkbox'
                    },

                    nameField: {
                        type: String,
                        required: false,
                        default: 'permissions'
                    },

                    idField: {
                        type: String,
                        required: false,
                        default: 'id'
                    },

                    valueField: {
                        type: String,
                        required: false,
                        default: 'value'
                    },

                    captionField: {
                        type: String,
                        required: false,
                        default: 'name'
                    },

                    childrenField: {
                        type: String,
                        required: false,
                        default: 'children'
                    },

                    items: {
                        type: [Array, String, Object],
                        required: false,
                        default: () => ([])
                    },

                    behavior: {
                        type: String,
                        required: false,
                        default: 'reactive'
                    },

                    value: {
                        type: [Array, String, Object],
                        required: false,
                        default: () => ([])
                    },

                    fallbackLocale: {
                        type: String,
                        required: false
                    },
                },

                data() {
                    return {
                        finalValues: []
                    }
                },

                computed: {
                    savedValues () {
                        if(! this.value)
                            return [];

                        if(this.inputType == 'radio')
                            return [this.value];

                        return (typeof this.value == 'string') ? JSON.parse(this.value) : this.value;
                    }
                },


                methods: {
                    generateChildren () {
                        let childElements = [];

                        let items = (typeof this.items == 'string') ? JSON.parse(this.items) : this.items;

                        items.forEach((item) => {
                            childElements.push(this.generateTreeItem(item));
                        })

                        return childElements;
                    },

                    generateTreeItem(item) {
                        return this.$h(this.$resolveComponent('v-tree-item'), {
                                items: item,
                                value: this.finalValues,
                                savedValues: this.savedValues,
                                nameField: this.nameField,
                                inputType: this.inputType,
                                captionField: this.captionField,
                                childrenField: this.childrenField,
                                valueField: this.valueField,
                                idField: this.idField,
                                behavior: this.behavior,
                                fallbackLocale: this.fallbackLocale,
                                on: {
                                    input: selection => {
                                        this.finalValues = selection;
                                    }
                                },
                            })
                    }
                },

                render () {
                    return this.$h('div', {
                            class: [
                                'tree-container',
                            ]
                        }, [this.generateChildren()]
                    )
                }
            });
        </script>

        {{-- v-tree-item component --}}
        <script type="module">
            app.component('v-tree-item', {
                inheritAttrs: false,
                
                props: {
                    inputType: {
                        type: String,
                    },

                    nameField: {
                        type: String,
                    },

                    idField: {
                        type: String,
                    },

                    captionField: {
                        type: String,
                    },

                    childrenField: {
                        type: String,
                    },

                    valueField: {
                        type: String,
                    },

                    items: {
                        type: [Array, String, Object],
                        required: false,
                        default: null,
                    },

                    value: {
                        type: Array,
                        required: false,
                        default: null,
                    },

                    behavior: {
                        type: String,
                        required: false,
                        default: 'reactive',
                    },

                    savedValues: {
                        type: Array,
                        required: false,
                        default: null,
                    },

                    fallbackLocale: {
                        type: String,
                        required: false,
                    },
                },

                mounted(){
                    console.log(this);
                },

                created() {
                    if (!this.savedValues) return;

                    let found = this.savedValues.filter(
                        (value) => value == this.items[this.valueField]
                    );

                    if (found.length) {
                        this.value.push(this.items);
                    }
                },

                computed: {
                    caption() {
                        return this.items[this.captionField]
                            ? this.items[this.captionField]
                            : this.items.translations.filter(
                                (translation) =>
                                    translation.locale === this.fallbackLocale
                            )[0][this.captionField];
                    },

                    allChildren() {
                        let leafs = [];

                        let searchTree = (items) => {
                            if (
                                !!items[this.childrenField] &&
                                this.getLength(items[this.childrenField]) > 0
                            ) {
                                if (typeof items[this.childrenField] == 'object') {
                                    for (let key in items[this.childrenField]) {
                                        searchTree(items[this.childrenField][key]);
                                    }
                                } else {
                                    items[this.childrenField].forEach((child) =>
                                        searchTree(child)
                                    );
                                }
                            } else {
                                leafs.push(items);
                            }
                        };

                        searchTree(this.items);

                        return leafs;
                    },

                    hasChildren() {
                        if (this.items) {
                            return (
                                !!this.items[this.childrenField] &&
                                this.getLength(this.items[this.childrenField]) > 0
                            );
                        }
                    },

                    hasSelection() {
                        return !!this.value && this.value.length > 0;
                    },

                    isAllChildrenSelected() {
                        return (
                            this.hasChildren &&
                            this.hasSelection &&
                            this.allChildren.every((leaf) =>
                                this.value.some(
                                    (sel) => sel[this.idField] === leaf[this.idField]
                                )
                            )
                        );
                    },

                    isSomeChildrenSelected() {
                        return (
                            this.hasChildren &&
                            this.hasSelection &&
                            this.allChildren.some((leaf) =>
                                this.value.some(
                                    (sel) => sel[this.idField] === leaf[this.idField]
                                )
                            )
                        );
                    },
                },  

                methods: {
                    getLength(items) {
                        return typeof items == 'object'
                            ? Object.keys(items).length
                            : items.length;
                    },

                    generateRoot() {
                        if (this.inputType == 'checkbox') {
                            if (this.behavior == 'reactive') {
                                return this.$h(this.$resolveComponent('v-tree-checkbox'), {
                                    id: this.items[this.idField],
                                    label: this.caption,
                                    nameField: this.nameField,
                                    modelValue: this.items[this.valueField],
                                    inputValue: this.hasChildren
                                        ? this.isSomeChildrenSelected
                                        : this.value,
                                    value: this.hasChildren
                                        ? this.isAllChildrenSelected
                                        : this.items,
                                    
                                    // on listner 
                                    on: {
                                        change: (selection) => {
                                            if (this.hasChildren) {
                                                if (this.isAllChildrenSelected) {
                                                    this.resetChildren();
                                                } else {
                                                    if (!selection) {
                                                        this.allChildren.forEach((leaf) => {
                                                            this.value.forEach((item, index) => {
                                                                if (item[this.idField] == leaf[this.idField]) {
                                                                    this.value.splice(index, 1);
                                                                }
                                                            });
                                                        });
                                                    } else {
                                                        this.allChildren.forEach((leaf) => {
                                                            let exists = false;

                                                            this.value.forEach((item) => {
                                                                if (item[this.idField] == leaf[this.idField]) {
                                                                    exists = true;
                                                                }
                                                            });

                                                            if (!exists) {
                                                                this.value.push(leaf);
                                                            }
                                                        });
                                                    }
                                                }

                                                this.$emit('input', this.value);
                                            } else {
                                                this.$emit('input', selection);
                                            }
                                        },
                                    },
                                });
                            } else {
                                return this.$h(this.$resolveComponent('v-tree-checkbox'), {
                                    id: this.items[this.idField],
                                    label: this.caption,
                                    nameField: this.nameField,
                                    modelValue: this.items[this.valueField],
                                    inputValue: this.value,
                                    value: this.items,
                                });
                            }
                        } else if (this.inputType == 'radio') {
                            return this.$h(this.$resolveComponent('v-tree-radio'), {
                                id: this.items[this.idField],
                                label: this.caption,
                                nameField: this.nameField,
                                modelValue: this.items[this.valueField],
                                value: this.savedValues,
                            });
                        }
                    },

                    generateChild(child) {
                        return this.$h(this.$resolveComponent('v-tree-item'), {
                            items: child,
                            value: this.value,
                            savedValues: this.savedValues,
                            nameField: this.nameField,
                            inputType: this.inputType,
                            captionField: this.captionField,
                            childrenField: this.childrenField,
                            valueField: this.valueField,
                            idField: this.idField,
                            behavior: this.behavior,
                            fallbackLocale: this.fallbackLocale,
                            onChange(selection) {
                                this.$emit('input', selection);
                            }
                        });
                    },

                    generateChildren() {
                        let childElements = [];

                        if (this.items) {
                            if (this.items[this.childrenField]) {
                                if (typeof this.items[this.childrenField] == 'object') {
                                    for (let key in this.items[this.childrenField]) {
                                        childElements.push(
                                            this.generateChild(
                                                this.items[this.childrenField][key]
                                            )
                                        );
                                    }
                                } else {
                                    this.items[this.childrenField].forEach((child) => {
                                        childElements.push(this.generateChild(child));
                                    });
                                }
                            }

                            return childElements;
                        }

                        return childElements;
                    },

                    generateIcon() {
                        return this.$h('i', {
                            class: ['icon-sort-right text-[24px]'],
                            
                            onClick: (selection) => {
                                this.$el.classList.toggle('active');
                            },
                        });
                    },

                    generateFolderIcon() {
                        return this.$h('i', {
                            class: ['icon-folder  text-[24px]'],
                        });
                    },

                    resetChildren() {
                        if (this.inputType == 'checkbox') {
                            this.allChildren.forEach((leaf) => {
                                let index = this.value.findIndex(
                                    (item) => item[this.idField] === leaf[this.idField]
                                );

                                this.value.splice(index, 1);
                            });
                        }
                    },
                },

                render() {
                    return this.$h(
                        'div', {
                            class: [
                                'pl-[30px]',
                                'active',
                                this.hasChildren ? 'has-children' : '',
                            ],
                        }, [
                            this.generateIcon(),
                            this.generateFolderIcon(),
                            this.generateRoot(),
                            ...this.generateChildren(),
                        ]
                    );
                },
            });
        </script>

        {{-- v-tree-checkbox template--}}
        <script type="text/x-template" id="v-tree-checkbox-template">
            <span class="inline-block">
                <label
                    :for="id"
                    class="flex gap-[10px] w-max p-[6px] items-center cursor-pointer select-none"
                >
                    <input
                        type="checkbox"
                        :name="[nameField + '[]']"
                        :value="modelValue"
                        :id="id"
                        class="hidden peer"
                        @change="inputChanged()"
                        :checked="isActive"
                    >

                    <label 
                        class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"
                        :for="id"
                    >
                    </label>

                    <div
                        class="text-[14px] text-gray-600 font-semibold cursor-pointer"
                        v-text="label"
                    >
                    </div>
                </label>
            </span>
        </script>

        {{-- v-tree-checkbox component --}}
        <script type="module">
            app.component('v-tree-checkbox', {
                template: '#v-tree-checkbox-template',

                name: 'tree-checkbox',

                props: ['id', 'label', 'nameField', 'modelValue', 'inputValue', 'value'],

                computed: {
                    isMultiple () {
                        return Array.isArray(this.internalValue)
                    },

                    isActive () {
                        const value = this.value
                        const input = this.internalValue

                        if (this.isMultiple) {
                            return input.some(item => this.valueComparator(item, value))
                        }

                        return value ? this.valueComparator(value, input) : Boolean(input)
                    },

                    internalValue: {
                        get () {
                            return this.lazyValue
                        },

                        set (val) {
                            this.lazyValue = val
                            this.$emit('input', val)
                        }
                    }
                },

                data: vm => ({
                    lazyValue: vm.inputValue
                }),

                watch: {
                    inputValue (val) {
                        this.internalValue = val
                    }
                },

                methods: {
                    inputChanged () {
                        const value = this.value
                        let input = this.internalValue

                        if (this.isMultiple) {
                            const length = input.length

                            input = input.filter(item => !this.valueComparator(item, value))

                            if (input.length === length) {
                                input.push(value)
                            }
                        } else {
                            input = !input
                        }

                        this.$emit('change', input)
                    },

                    valueComparator (a, b) {
                        if (a === b) 
                            return true

                        if (a !== Object(a) || b !== Object(b)) {
                            return false
                        }

                        const props = Object.keys(a)

                        if (props.length !== Object.keys(b).length) {
                            return false
                        }

                        return props.every(p => this.valueComparator(a[p], b[p]))
                    }
                }
            });
        </script>

        {{-- v-tree-radio-component template --}}
        <script type="text/x-template" id="v-tree-radio-template">
            <span class="radio">
                <input 
                    type="radio"
                    :id="id"
                    :name="nameField"
                    :value="modelValue"
                    :checked="isActive"
                >
                
                <label class="radio-view" :for="id"></label>
                
                <span class="" :for="id" v-text="label"></span>
            </span>
        </script>

        {{-- v-tree-radio component --}}
        <script type="module">
            app.component('v-tree-radio', {
                template: '#v-tree-radio-template',

                name: 'tree-radio',

                props: ['id', 'label', 'nameField', 'modelValue', 'value'],

                computed: {
                    isActive () {
                        if(this.value.length) {
                            return this.value[0] == this.modelValue ? true : false;
                        }

                        return false
                    }
                }
            });
        </script>
      @endPushOnce
</x-admin::layouts>