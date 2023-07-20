<x-admin::layouts>    
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.promotions.catalog-rules.add-title')
    </x-slot:title>

    {{-- Create Catalog form --}}
    <v-catalog-rule-create-form></v-catalog-rule-create-form>

    @pushOnce('scripts')
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
                                                <option value="{{ $customerGroup->id }}" {{ old('customer_groups') && in_array($customerGroup->id, old('customer_groups')) ? 'selected' : '' }}>
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
                                    <template 
                                        v-slot:default="{ removeCondition }"
                                    >
                                        <div class="flex gap-[16px] justify-between mt-[15px]">
                                            <div class="flex gap-[16px] flex-1 max-sm:flex-wrap max-sm:flex-1">
                                                <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-[196px] text-[14px] text-gray-400 py-[6px] px-[8px] text-center  bg-white border border-gray-300 rounded-[6px] cursor-pointer transition-all hover:border-gray-400 max-sm:flex-auto max-sm:max-w-full">
                                                    Subtotal<span class="icon-sort-down text-[24px]"></span>
                                                </div>
                                                <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-[196px] text-[14px] text-gray-400 py-[6px] px-[8px] text-center  bg-white border border-gray-300 rounded-[6px] cursor-pointer transition-all hover:border-gray-400 max-sm:flex-auto max-sm:max-w-full">
                                                    Is above<span class="icon-sort-down text-[24px]"></span>
                                                </div>
                
                                                <input class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full max-w-[196px] py-[6px] px-[8px] transition-all hover:border-gray-400 max-sm:flex-auto max-sm:max-w-full" type="text" placeholder="Anie">
                                            </div>
                
                                            <span 
                                                class="icon-delete text-[24px] p-[6px]  rounded-[6px] cursor-pointer transition-all hover:bg-gray-100 max-sm:place-self-center"
                                                @click="removeCondition"
                                            >
                                            </span>
                                        </div>
                                    </template>
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
    @endpushOnce

    @pushOnce('scripts')
        <script type="text/x-template" id="v-catalog-rule-condition-item-template">
            <div>
                <slot
                    :removeCondition="removeCondition"
                ></slot>
            </div>
        </script>

        <script type="module">
            app.component('v-catalog-rule-condition-item', {
                template: "#v-catalog-rule-condition-item-template",

                props: ['index', 'condition'],

                methods: {
                    removeCondition() {
                        this.$emit('onRemoveCondition', this.condition)
                    }
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>