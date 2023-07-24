<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        Cart rules
    </x-slot:title>

    <v-cart-rule-create-form></v-cart-rule-create-form>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-cart-rule-create-form-template">
           
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

                                <div class="mb-[10px]">
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
                                </div>
                            </div>
                
                            {{-- Condition --}}
                            <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                
                                <div class="flex gap-[16px] items-center justify-between">
                                    <p class="text-[16px] text-gray-800 font-semibold">Conditions</p>
                
                                    <div
                                        class="inline-flex gap-x-[8px] items-center justify-betweew text-[14px] text-gray-400 py-[6px] px-[8px] text-center  bg-white border border-gray-300 rounded-[6px] cursor-pointer transition-all hover:border-gray-400">
                                        All Conditions are True<span class="icon-sort-down text-[24px]"></span>
                                    </div>
                                </div>
                
                                <div class="flex gap-[16px] justify-between mt-[15px]">
                                    <div class="flex gap-[16px] flex-1 max-sm:flex-wrap max-sm:flex-1">
                                        <div
                                            class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-[196px] text-[14px] text-gray-400 py-[6px] px-[8px] text-center  bg-white border border-gray-300 rounded-[6px] cursor-pointer transition-all hover:border-gray-400 max-sm:flex-auto max-sm:max-w-full">
                                            Subtotal<span class="icon-sort-down text-[24px]"></span>
                                        </div>
                                        <div
                                            class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-[196px] text-[14px] text-gray-400 py-[6px] px-[8px] text-center  bg-white border border-gray-300 rounded-[6px] cursor-pointer transition-all hover:border-gray-400 max-sm:flex-auto max-sm:max-w-full">
                                            Is above<span class="icon-sort-down text-[24px]"></span>
                                        </div>
                
                                        <input
                                            class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full max-w-[196px] py-[6px] px-[8px] transition-all hover:border-gray-400 max-sm:flex-auto max-sm:max-w-full"
                                            type="text" placeholder="Anie">
                                    </div>
                
                                    <span
                                        class="icon-delete text-[24px] p-[6px]  rounded-[6px] cursor-pointer transition-all hover:bg-gray-100 max-sm:place-self-center"></span>
                                </div>
                
                                <div
                                    class="max-w-max px-[12px] py-[5px] mt-[15px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer">
                                    Add Condition</div>
                
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
                            {{-- component 1 --}}
                            <div class="bg-white rounded-[4px] box-shadow">
                                {{-- Settings --}}
                                <x-admin::accordion>
                                    <x-slot:header>
                                        <div class="flex items-center justify-between p-[6px]">
                                            <p class="text-gray-600 text-[16px] p-[10px] font-semibold">
                                                Settings
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
    
                                        <div class="mb-[10px]">
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
                                        </div>

                                        <div class="mb-[10px]">
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('Coupon Type')
                                                </x-admin::form.control-group.label>
    
                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="coupon_type"
                                                    :value="old('coupon_type')"
                                                    id="coupon_type"
                                                    :label="trans('Coupon Type')"
                                                    :placeholder="trans('Coupon Type')"
                                                >
                                                </x-admin::form.control-group.control>
    
                                                <x-admin::form.control-group.error
                                                    control-name="coupon_type"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>

                                        <template v-if="parseInt(coupon_type)">
                                            
                                        </template>
                                    </x-slot:content>
                                </x-admin::accordion>
                            </div>
                
                            {{-- component 2 --}}
                            <div class="bg-white rounded-[4px] box-shadow">
                                <div class="flex items-center justify-between p-[6px]">
                                    <p class="text-gray-600 text-[16px] p-[10px] font-semibold">Marketing Time</p>
                                    <span
                                        class="icon-arrow-up text-[24px] p-[6px]  rounded-[6px] cursor-pointer transition-all hover:bg-gray-100"></span>
                                </div>
                
                                <div class="px-[16px] pb-[16px]">
                                    <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username"> Uses
                                        Per Customer*
                                    </label>
                                    <div class="flex gap-[16px]">
                                        <div
                                            class="inline-flex gap-x-[8px] items-center justify-between text-[14px] text-gray-400 py-[6px] px-[10px] text-center leading-[24px] w-full bg-white border border-gray-300 rounded-[6px] cursor-pointer transition-all hover:border-gray-400">
                                            From<span class="icon-calendar text-[24px]"></span>
                                        </div>
                                        <div
                                            class="inline-flex gap-x-[8px] items-center justify-between text-[14px] text-gray-400 py-[6px] px-[10px] text-center leading-[24px] w-full bg-white border border-gray-300 rounded-[6px] cursor-pointer transition-all hover:border-gray-400">
                                            From<span class="icon-calendar text-[24px]"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    @endPushOnce
</x-admin::layouts>
