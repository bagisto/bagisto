<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        Cart rules
    </x-slot:title>

    <v-cart-rule-create-form></v-cart-rule-create-form>

    @pushOnce('scripts')
        {{-- v cart rules create form template --}}
        <script
            type="text/x-template"
            id="v-cart-rule-create-form-template"
        >
            <div>
                <x-admin::form 
                    :action="route('admin.catalog_rules.store')"
                    enctype="multipart/form-data"
                >
                    <div class="grid">
                        <div class="flex justify-end items-center pt-[11px] cursor-pointer">
                            <button 
                                type="submit"
                                class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                            >
                                Save Cart Rule
                            </button>
                        </div>
                    </div>
                
                    {{-- body content  --}}
                    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                        {{-- Left sub-component --}}
                        <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                            {{-- General --}}
                            <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                                <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                                    @lang('General')
                                </p>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        Name
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        :value="old('name')"
                                        id="name"
                                        rules="required"
                                        :label="trans('Name')"
                                        :placeholder="trans('Name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        Description
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="textarea"
                                        name="description"
                                        :value="old('description')"
                                        id="description"
                                        rules="required"
                                        :label="trans('Description')"
                                        :placeholder="trans('Description')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="description"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <div class="mb-[10px]">
                                    <p class="block leading-[24px] text-gray-800 font-medium">
                                        @lang('admin::app.promotions.catalog-rules.create.channels')
                                    </p>
                                    
                                    @foreach(core()->getAllChannels() as $channel)
                                        <label
                                            class="flex gap-[10px] w-full items-center p-[6px] cursor-pointer select-none hover:bg-gray-100 hover:rounded-[8px]"
                                            for="channel_{{ $channel->id }}"
                                        >
                                            <input
                                                type="checkbox"
                                                name="channels[]"
                                                id="channel_{{ $channel->id }}"
                                                value="{{ $channel->id }}"
                                                {{ in_array($channel->id, old('channels[]', [])) ? 'checked' : '' }}
                                                class="hidden peer"
                                            >
                                            
                                            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
                                            
                                            <p class="text-gray-600 font-semibold cursor-pointer">
                                                {{ core()->getChannelName($channel) }}
                                            </p>
                                        </label>
                                    @endforeach
                                </div>

                                <div class="mb-[10px]">
                                    <p class="block leading-[24px] text-gray-800 font-medium">
                                        @lang('admin::app.promotions.catalog-rules.create.customer-groups')
                                    </p>
                                    
                                    @foreach(app('Webkul\Customer\Repositories\CustomerGroupRepository')->all() as $customerGroup)
                                        <label
                                            class="flex gap-[10px] w-full items-center p-[6px] cursor-pointer select-none hover:bg-gray-100 hover:rounded-[8px]"
                                            for="customer_group_{{ $customerGroup->id }}"
                                        >
                                            <input
                                                type="checkbox"
                                                name="customer_groups[]"
                                                id="customer_group_{{ $customerGroup->id }}"
                                                value="{{ $customerGroup->id }}"
                                                {{ in_array($customerGroup->id, old('customer_groups[]', [])) ? 'checked' : '' }}
                                                class="hidden peer"
                                            >
                                            
                                            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
                                            
                                            <p class="text-gray-600 font-semibold cursor-pointer">
                                                {{ $customerGroup->name }}
                                            </p>
                                        </label>
                                    @endforeach
                                </div>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('Coupon Type')
                                    </x-admin::form.control-group.label>
                                
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="coupon_type"
                                        id="coupon_type"
                                        rules="required"
                                        :label="trans('Coupon Type')"
                                        :placeholder="trans('Coupon Type')"
                                        v-model="coupon_type"
                                    >
                                        <option 
                                            value="0"
                                            {{ old('coupon_type') == 0 ? 'selected' : '' }}
                                        >
                                            @lang('No Coupon')
                                        </option>
                                
                                        <option 
                                            value="1"
                                            {{ old('coupon_type') == 1 ? 'selected' : '' }}
                                        >
                                            @lang('Specific Coupon')
                                        </option>
                                    </x-admin::form.control-group.control>
                                
                                    <x-admin::form.control-group.error
                                        control-name="coupon_type"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                                
                                <template v-if="parseInt(coupon_type)">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('Auto Generate Coupon')
                                        </x-admin::form.control-group.label>
                                
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="use_auto_generation"
                                            id="use_auto_generation"
                                            rules="required"
                                            :label="trans('Auto Generate Coupon')"
                                            :placeholder="trans('Auto Generate Coupon')"
                                            v-model="use_auto_generation"
                                        >
                                            <option 
                                                value="0"
                                                {{ old('use_auto_generation') == 0 ? 'selected' : '' }}
                                            >
                                                @lang('No')
                                            </option>
                                
                                            <option 
                                                value="1"
                                                {{ old('use_auto_generation') == 1 ? 'selected' : '' }}
                                            >
                                                @lang('Yes')
                                            </option>
                                        </x-admin::form.control-group.control>
                                
                                        <x-admin::form.control-group.error
                                            control-name="coupon_type"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                
                                    <x-admin::form.control-group
                                        class="mb-[10px]"
                                        v-if="! parseInt(use_auto_generation)"
                                    >
                                        <x-admin::form.control-group.label>
                                            @lang('Coupon Code')
                                        </x-admin::form.control-group.label>
                                
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="coupon_code"
                                            id="coupon_code"
                                            rules="required"
                                            :label="trans('Coupon Code')"
                                            :placeholder="trans('Coupon Code')"
                                        >
                                        </x-admin::form.control-group.control>
                                
                                        <x-admin::form.control-group.error
                                            control-name="coupon_code"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                    
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('Uses Per Coupon')
                                        </x-admin::form.control-group.label>
                                
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="uses_per_coupon"
                                            id="uses_per_coupon"
                                            :label="trans('Uses per coupon')"
                                            :placeholder="trans('Uses per coupon')"
                                        >
                                        </x-admin::form.control-group.control>
                                
                                        <x-admin::form.control-group.error
                                            control-name="uses_per_coupon"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </template>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('Uses Per Customer')
                                    </x-admin::form.control-group.label>
                            
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="usage_per_customer"
                                        id="usage_per_customer"
                                        :label="trans('Uses per Customer')"
                                        :placeholder="trans('Uses per Customer')"
                                    >
                                    </x-admin::form.control-group.control>
                            
                                    <x-admin::form.control-group.error
                                        control-name="usage_per_customer"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                
                            </div>
                
                            {{-- Conditions --}}
                            <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                                <div class="flex gap-[16px] items-center justify-between">
                                    <p class="text-[16px] text-gray-800 font-semibold">
                                        @lang('admin::app.promotions.catalog-rules.create.conditions')
                                    </p>

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.promotions.catalog-rules.create.condition-type')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="condition_type"
                                            id="condition_type"
                                            class="pr-[40px] text-gray-400 border-gray-300"
                                            :label="trans('admin::app.promotions.catalog-rules.condition-type')"
                                            :placeholder="trans('admin::app.promotions.catalog-rules.condition-type')"
                                            v-model="condition_type"
                                        >
                                            <option value="1">
                                                @lang('admin::app.promotions.catalog-rules.create.all-conditions-true')
                                            </option>

                                            <option value="2">
                                                @lang('admin::app.promotions.catalog-rules.create.any-conditions-true')
                                            </option>
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="condition_type"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>
        
                                <v-cart-rule-condition-item
                                    v-for='(condition, index) in conditions'
                                    :condition="condition"
                                    :key="index"
                                    :index="index"
                                    @onRemoveCondition="removeCondition($event)">
                                >
                                </v-cart-rule-condition-item>
                      
                                <div 
                                    class="max-w-max px-[12px] py-[5px] mt-[15px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                    @click="addCondition"
                                >
                                    @lang('admin::app.promotions.catalog-rules.create.add-condition')
                                </div>
        
                            </div>

                            {{-- Action --}}
                            <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                                <div class="grid gap-[6px]">
                                    <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">Actions</p>
                
                                    <div class="flex  gap-[16px]  max-sm:flex-wrap">
                                        <div class="w-full mb-[10px]">
                                            <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username">
                                                Discount Type* </label>
                                            <input
                                                class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400"
                                                type="text" placeholder="Percentage of Product Price">
                                        </div>
                                        <div class="w-full mb-[10px]">
                                            <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username">
                                                Discount Type* </label>
                                            <input
                                                class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400"
                                                type="text" placeholder="Percentage of Product Price">
                                        </div>
                                    </div>
                                    <div class="flex  gap-[16px]  max-sm:flex-wrap">
                                        <div class="w-full mb-[10px]">
                                            <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username">
                                                Maximum quantity allowed to be discounted*
                                            </label>
                                            <input
                                                class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400"
                                                type="text" placeholder="0">
                                        </div>
                                        <div class="w-full mb-[10px]">
                                            <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username">
                                                Buy X Quantity*
                                            </label>
                                            <input
                                                class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400"
                                                type="text" placeholder="0">
                                        </div>
                                    </div>
                
                                    <div class="flex  gap-[16px]  max-sm:flex-wrap">
                                        <div class="w-full mb-[10px]">
                                            <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username">
                                                Apply to shipping* </label>
                                            <div
                                                class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 text-[14px] font-normal py-[6px] px-[12px] text-center w-full bg-white border border-gray-300 rounded-[6px] cursor-pointertransition-all hover:border-gray-400">
                                                No<span class="icon-sort-down text-[24px]"></span>
                                            </div>
                                        </div>
                                        <div class="w-full mb-[10px]">
                                            <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username">
                                                Free Shipping* </label>
                                            <div
                                                class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 text-[14px] font-normal py-[6px] px-[12px] text-center w-full bg-white border border-gray-300 rounded-[6px] cursor-pointertransition-all hover:border-gray-400">
                                                6<span class="icon-sort-down text-[24px]"></span>
                                            </div>
                                        </div>
                                    </div>
                
                                    <div class="flex  gap-[16px] justify-between max-sm:flex-wrap">
                                        <div class="w-full mb-[10px]">
                                            <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username">
                                                Apply to shipping*
                                            </label>
                                            <div
                                                class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 text-[14px] font-normal py-[6px] px-[12px] text-center w-1/2 bg-white border border-gray-300 rounded-[6px] cursor-pointertransition-all hover:border-gray-400 max-sm:w-full">
                                                No<span class="icon-sort-down text-[24px]"></span>
                                            </div>
                                        </div>
                                    </div>
                
                                </div>
                            </div>
                        </div>

                        {{-- Right sub-component --}}
                        <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                            {{-- Settings --}}
                            <x-admin::accordion>
                                <x-slot:header>
                                    <div class="flex items-center justify-between p-[6px]">
                                        <p class="text-gray-600 text-[16px] p-[10px] font-semibold">
                                            @lang('Settings')
                                        </p>
                                    </div>
                                </x-slot:header>
                            
                                <x-slot:content>
                                    <label
                                        for="checkbox"
                                        class="flex gap-[10px] w-full items-center p-[6px] cursor-pointer select-none hover:bg-gray-100 hover:rounded-[8px]"
                                    >
                                        <input
                                            type="checkbox"
                                            class="hidden peer"
                                            id="checkbox"
                                            name="status"
                                            value="1"
                                        >

                                        <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>

                                        <div
                                            class="text-[14px] text-gray-600 font-semibold cursor-pointer"
                                        >
                                            @lang('admin::app.promotions.catalog-rules.create.status')
                                        </div>

                                    </label>

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('Priority')
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

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('Usage per customer')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="usage_per_customer"
                                            id="usage_per_customer"
                                            :label="trans('Usage per customer')"
                                            :placeholder="trans('Usage per customer')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="usage_per_customer"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </x-slot:content>
                            </x-admin::accordion>
                
                            {{-- Marketing Time --}}
                            <x-admin::accordion>
                                <x-slot:header>
                                    <div class="flex items-center justify-between p-[6px]">
                                        <p class="text-gray-600 text-[16px] p-[10px] font-semibold">
                                            @lang('Marketing Time')
                                        </p>
                                    </div>
                                </x-slot:header>
                            
                                <x-slot:content>
                                    <div class="px-[16px] pb-[16px]">
                                        <div class="flex gap-[16px]">
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('From')
                                                </x-admin::form.control-group.label>
            
                                                <x-admin::form.control-group.control
                                                    type="date"
                                                    name="starts_from"
                                                    :value="old('starts_from')"
                                                    id="starts_from"
                                                    :label="trans('admin::app.promotions.catalog-rules.create.from')"
                                                    :placeholder="trans('admin::app.promotions.catalog-rules.create.from')"
                                                >
                                                </x-admin::form.control-group.control>
            
                                                <x-admin::form.control-group.error
                                                    control-name="starts_from"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>

                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('To')
                                                </x-admin::form.control-group.label>
            
                                                <x-admin::form.control-group.control
                                                    type="date"
                                                    name="ends_till"
                                                    :value="old('ends_till')"
                                                    id="ends_till"
                                                    :label="trans('admin::app.promotions.catalog-rules.create.to')"
                                                    :placeholder="trans('admin::app.promotions.catalog-rules.create.to')"
                                                >
                                                </x-admin::form.control-group.control>
            
                                                <x-admin::form.control-group.error
                                                    control-name="ends_till"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                    </div>
                                </x-slot:content>
                            </x-admin::accordion>
                        </div>
                    </div>
                </x-admin::form>
           </div>
        </script>

        <script type="module">
            app.component('v-cart-rule-create-form', {
                template: '#v-cart-rule-create-form-template',

                data: function() {
                    return {
                        coupon_type: 0,

                        use_auto_generation: 0,

                        condition_type: 1,

                        conditions: [],

                        action_type: "{{ old('action_type') ?: 'by_percent' }}"
                    }
                },

                methods: {
                    addCondition: function() {
                        this.conditions.push({
                            'attribute': '',
                            'operator': '==',
                            'value': '',
                        });
                    },

                    removeCondition: function(condition) {
                        let index = this.conditions.indexOf(condition)

                        this.conditions.splice(index, 1)
                    },

                    onSubmit: function(e) {
                        this.$root.onSubmit(e)
                    },

                    onSubmit: function(e) {
                        this.$root.onSubmit(e)
                    },

                    redirectBack: function(fallbackUrl) {
                        this.$root.redirectBack(fallbackUrl)
                    }
                }
            })
        </script>

        {{-- v catalog rule condition item form template --}}
        <script 
            type="text/x-template"
            id="v-cart-rule-condition-item-template"
        >
            <div class="flex gap-[16px] justify-between mt-[15px]">
                <div class="flex gap-[16px] flex-1 max-sm:flex-wrap max-sm:flex-1">
                    <select
                        :name="['conditions[' + index + '][attribute]']"
                        :id="['conditions[' + index + '][attribute]']"
                        class="inline-flex gap-x-[4px] justify-between items-center max-h-[40px] w-full max-w-[196px] py-[6px] pl-[12px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400 max-sm:flex-auto max-sm:max-w-full"
                        v-model="condition.attribute"
                    >
                        <option value="">@lang('admin::app.promotions.catalog-rules.create.choose-condition-to-add')</option>

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
                        class="inline-flex gap-x-[4px] justify-between items-center max-h-[40px] w-full max-w-[196px] py-[6px] pl-[12px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400"
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
                                v-if="matchedAttribute.type == 'text' 
                                    || matchedAttribute.type == 'price'
                                    || matchedAttribute.type == 'decimal'
                                    || matchedAttribute.type == 'integer'"
                            >
                                <v-field 
                                    :name="`['conditions[${index}][value]']`"
                                    v-slot="{ field, errorMessage }"
                                    :id="`['conditions[${index}][value]']`"
                                    :rules="
                                        matchedAttribute.type == 'price' ? 'decimal:2' : ''
                                        || matchedAttribute.type == 'decimal' ? 'decimal:2' : ''
                                        || matchedAttribute.type == 'integer' ? 'decimal:2' : ''
                                        || matchedAttribute.type == 'text' ? 'regex:^([A-Za-z0-9_ \'\-]+)$' : ''"
                                    label="Conditions"
                                    v-model="condition.value"
                                >
                                    <input 
                                        type="text"
                                        v-bind="field"
                                        :class="{ 'border border-red-500': errorMessage }"
                                        class="w-full py-2 px-3 appearance-none border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400"
                                    />
                                </v-field>
                                
                                <v-error-message
                                    :name="`['conditions[${index}][value]']`"
                                    class="mt-1 text-red-500 text-xs italic"
                                    as="p"
                                />
                            </div>

                            <div v-if="matchedAttribute.type == 'date'">
                                <input 
                                    type="date"
                                    :name="['conditions[' + index + '][value]']"
                                    class="border w-full py-2 px-3 appearance-none rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400"
                                    v-model="condition.value"
                                />
                            </div>

                            <div v-if="matchedAttribute.type == 'datetime'">
                                <input 
                                    type="datetime"
                                    :name="['conditions[' + index + '][value]']"
                                    class="border w-full py-2 px-3 appearance-none rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400"
                                    v-model="condition.value"
                                />
                            </div>

                            <div v-if="matchedAttribute.type == 'boolean'">
                                <select 
                                    :name="['conditions[' + index + '][value]']"
                                    class="inline-flex gap-x-[4px] justify-between items-center max-h-[40px] w-full max-w-[196px] py-[6px] pl-[12px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400"
                                    v-model="condition.value"
                                >
                                    <option value="1">
                                        @lang('admin::app.promotions.catalog-rules.create.yes')
                                    </option>

                                    <option value="0">
                                        @lang('admin::app.promotions.catalog-rules.create.no')
                                    </option>
                                </select>
                            </div>

                            <div v-if="matchedAttribute.type == 'select' || matchedAttribute.type == 'radio'">
                                <select
                                    :name="['conditions[' + index + '][value]']"
                                    class="inline-flex gap-x-[4px] justify-between items-center max-h-[40px] w-full max-w-[196px] py-[6px] pl-[12px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400"
                                    v-if="matchedAttribute.key != 'catalog|state'"
                                    v-model="condition.value"
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
                                    class="inline-flex gap-x-[4px] justify-between items-center max-h-[40px] w-full max-w-[196px] py-[6px] pl-[12px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400"
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

                            <div v-if="matchedAttribute.type == 'multiselect' || matchedAttribute.type == 'checkbox'">
                                <select 
                                    :name="['conditions[' + index + '][value][]']"
                                    class="inline-flex gap-x-[4px] justify-between items-center max-h-[40px] w-full max-w-[196px] py-[6px] pl-[12px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400"
                                    v-model="condition.value"
                                    multiple
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
                    class="icon-delete max-h-[36px] max-w-[36px] text-[24px] p-[6px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-100 max-sm:place-self-center"
                    @click="removeCondition"
                >
                </span>
            </div>
        </script>

        {{-- v catalog rule condition item component --}}
        <script type="module">
            app.component('v-cart-rule-condition-item', {
                template: "#v-cart-rule-condition-item-template",

                props: ['index', 'condition'],

                data() {
                    return {
                        conditionAttributes: @json(app('\Webkul\CartRule\Repositories\CartRuleRepository')->getConditionAttributes()),

                        attribute_type_indexes: {
                            'cart': 0,

                            'cart_item': 1,

                            'product': 2
                        },

                        condition_operators: {
                            'price': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-not-equal-to') }}'
                            }, {
                                'operator': '>=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-greater-than') }}'
                            }, {
                                'operator': '<=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-less-than') }}'
                            }, {
                                'operator': '>',
                                'label': '{{ __('admin::app.promotions.cart-rules.greater-than') }}'
                            }, {
                                'operator': '<',
                                'label': '{{ __('admin::app.promotions.cart-rules.less-than') }}'
                            }],
                            'decimal': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-not-equal-to') }}'
                            }, {
                                'operator': '>=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-greater-than') }}'
                            }, {
                                'operator': '<=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-less-than') }}'
                            }, {
                                'operator': '>',
                                'label': '{{ __('admin::app.promotions.cart-rules.greater-than') }}'
                            }, {
                                'operator': '<',
                                'label': '{{ __('admin::app.promotions.cart-rules.less-than') }}'
                            }],
                            'integer': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-not-equal-to') }}'
                            }, {
                                'operator': '>=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-greater-than') }}'
                            }, {
                                'operator': '<=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-less-than') }}'
                            }, {
                                'operator': '>',
                                'label': '{{ __('admin::app.promotions.cart-rules.greater-than') }}'
                            }, {
                                'operator': '<',
                                'label': '{{ __('admin::app.promotions.cart-rules.less-than') }}'
                            }],
                            'text': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-not-equal-to') }}'
                            }, {
                                'operator': '{}',
                                'label': '{{ __('admin::app.promotions.cart-rules.contain') }}'
                            }, {
                                'operator': '!{}',
                                'label': '{{ __('admin::app.promotions.cart-rules.does-not-contain') }}'
                            }],
                            'boolean': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-not-equal-to') }}'
                            }],
                            'date': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-not-equal-to') }}'
                            }, {
                                'operator': '>=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-greater-than') }}'
                            }, {
                                'operator': '<=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-less-than') }}'
                            }, {
                                'operator': '>',
                                'label': '{{ __('admin::app.promotions.cart-rules.greater-than') }}'
                            }, {
                                'operator': '<',
                                'label': '{{ __('admin::app.promotions.cart-rules.less-than') }}'
                            }],
                            'datetime': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-not-equal-to') }}'
                            }, {
                                'operator': '>=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-greater-than') }}'
                            }, {
                                'operator': '<=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-less-than') }}'
                            }, {
                                'operator': '>',
                                'label': '{{ __('admin::app.promotions.cart-rules.greater-than') }}'
                            }, {
                                'operator': '<',
                                'label': '{{ __('admin::app.promotions.cart-rules.less-than') }}'
                            }],
                            'select': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-not-equal-to') }}'
                            }],
                            'radio': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-not-equal-to') }}'
                            }],
                            'multiselect': [{
                                'operator': '{}',
                                'label': '{{ __('admin::app.promotions.cart-rules.contains') }}'
                            }, {
                                'operator': '!{}',
                                'label': '{{ __('admin::app.promotions.cart-rules.does-not-contain') }}'
                            }],
                            'checkbox': [{
                                'operator': '{}',
                                'label': '{{ __('admin::app.promotions.cart-rules.contains') }}'
                            }, {
                                'operator': '!{}',
                                'label': '{{ __('admin::app.promotions.cart-rules.does-not-contain') }}'
                            }]
                        }
                    }
                },

            computed: {
                matchedAttribute() {
                    if (this.condition.attribute == '')
                        return;

                    let self = this;

                    let attributeIndex = this.attribute_type_indexes[this.condition.attribute.split("|")[0]];

                    matchedAttribute = this.condition_attributes[attributeIndex]['children'].filter(function(
                        attribute) {
                        return attribute.key == self.condition.attribute;
                    });

                    if (matchedAttribute[0]['type'] == 'multiselect' || matchedAttribute[0]['type'] ==
                        'checkbox') {
                        this.condition.operator = '{}';

                        this.condition.value = [];
                    }

                    return matchedAttribute[0];
                }
            },

            methods: {
                removeCondition: function() {
                    this.$emit('onRemoveCondition', this.condition)
                }
            }
            });
        </script>

        {{-- v tree view --}}
        @include('admin::tree.view')

        {{-- v tree item --}}
        @include('admin::tree.item')

        {{-- v tree checkbox --}}
        @include('admin::tree.item')

        {{-- v tree checkbox --}}
        @include('admin::tree.checkbox')

        {{-- v tree radio --}}
        @include('admin::tree.radio')
    @endPushOnce
</x-admin::layouts>
