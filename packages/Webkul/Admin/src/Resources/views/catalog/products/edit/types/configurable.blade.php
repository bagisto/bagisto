{!! view_render_event('bagisto.admin.catalog.product.edit.form.types.configurable.before', ['product' => $product]) !!}

<v-product-variations :errors="errors"></v-product-variations>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.types.configurable.after', ['product' => $product]) !!}

@pushOnce('scripts')
    {{-- Variations Template --}}
    <script type="text/x-template" id="v-product-variations-template">
        <div class="relative bg-white dark:bg-gray-900  rounded-[4px] box-shadow">
            <!-- Panel Header -->
            <div class="flex flex-wrap gap-[10px] justify-between mb-[10px] p-[16px]">
                <div class="flex flex-col gap-[8px]">
                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                        @lang('admin::app.catalog.products.edit.types.configurable.title')
                    </p>

                    <p class="text-[12px] text-gray-500 dark:text-gray-300 font-medium">
                        @lang('admin::app.catalog.products.edit.types.configurable.info')
                    </p>
                </div>
                
                <!-- Add Button -->
                <div class="flex gap-x-[4px] items-center">
                    <div
                        class="secondary-button"
                        @click="$refs.variantCreateModal.open()"
                    >
                        @lang('admin::app.catalog.products.edit.types.configurable.add-btn')
                    </div>
                </div>
            </div>

            <template v-if="variants.length">
                <!-- Mass Action Vue Component -->
                <v-product-variations-mass-action
                    :super-attributes="superAttributes"
                    :variants="variants"
                >
                </v-product-variations-mass-action>

                <!-- Panel Content -->
                <div class="grid">
                    <v-product-variation-item
                        v-for='(variant, index) in variants'
                        :key="index"
                        :index="index"
                        :variant="variant"
                        :attributes="superAttributes"
                        @onRemoved="removeVariant"
                        :errors="errors"
                    ></v-product-variation-item>
                </div>
            </template>

            <!-- For Empty Variations -->
            <template v-else>
                <div class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]">
                    <!-- Placeholder Image -->
                    <img
                        src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                        class="w-[80px] h-[80px] dark:invert dark:mix-blend-exclusion"
                    />

                    <!-- Add Variants Information -->
                    <div class="flex flex-col items-center">
                        <p class="text-[16px] text-gray-400 font-semibold">
                            @lang('admin::app.catalog.products.edit.types.configurable.empty-title')
                        </p>

                        <p class="text-gray-400">
                            @lang('admin::app.catalog.products.edit.types.configurable.empty-info')
                        </p>
                    </div>
                    
                    <!-- Add Row Button -->
                    <div
                        class="secondary-button text-[14px]"
                        @click="$refs.variantCreateModal.open()"
                    >
                        @lang('admin::app.catalog.products.edit.types.configurable.add-btn')
                    </div>
                </div>
            </template>

            <!-- Add Variant Form Modal -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, addVariant)">
                    <!-- Customer Create Modal -->
                    <x-admin::modal ref="variantCreateModal">
                        <x-slot:header>
                            <!-- Modal Header -->
                            <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                                @lang('admin::app.catalog.products.edit.types.configurable.create.title')
                            </p>
                        </x-slot:header>
        
                        <x-slot:content>
                            <!-- Modal Content -->
                            <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800  ">
                                <x-admin::form.control-group
                                    v-for='(attribute, index) in superAttributes'
                                >
                                    <x-admin::form.control-group.label class="required">
                                        @{{ attribute.admin_name }}
                                    </x-admin::form.control-group.label>

                                    <v-field
                                        as="select"
                                        :name="attribute.code"
                                        class="custom-select flex w-full min-h-[39px] py-[6px] px-[12px] bg-white dark:bg-gray-900  border dark:border-gray-800   rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                                        :class="[errors[attribute.code] ? 'border border-red-500' : '']"
                                        rules="required"
                                        :label="attribute.admin_name"
                                    >
                                        <option
                                            v-for="option in attribute.options"
                                            :value="option.id"
                                        >
                                            @{{ option.admin_name }}
                                        </option>
                                    </v-field>

                                    <v-error-message
                                        :name="attribute.code"
                                        v-slot="{ message }"
                                    >
                                        <p
                                            class="mt-1 text-red-600 text-xs italic"
                                            v-text="message"
                                        >
                                        </p>
                                    </v-error-message>
                                </x-admin::form.control-group>
                            </div>
                        </x-slot:content>
        
                        <x-slot:footer>
                            <!-- Modal Submission -->
                            <div class="flex gap-x-[10px] items-center">
                                <button 
                                    type="submit"
                                    class="primary-button"
                                >
                                    @lang('admin::app.catalog.products.edit.types.configurable.create.save-btn')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </div>
    </script>

    {{-- Variations Mass Action Template --}}
    <script type="text/x-template" id="v-product-variations-mass-action-template">
        <!-- Mass Actions -->
        <div class="flex gap-[5px] items-center px-[16px]">
            <span
                class="flex icon-uncheckbox text-[24px] cursor-pointer select-none"
                :class="{
                    '!icon-checked text-blue-600': variants.length == selectedVariants.length,
                    '!icon-checkbox-partial text-blue-600': selectedVariants.length && variants.length != selectedVariants.length
                }"
                for="select-all-variants"
                @click="selectAll"
            >
            </span>

            <!-- Attribute Options Selector -->
            <x-admin::dropdown v-bind:close-on-click="false">
                <!-- Dropdown Toggler -->
                <x-slot:toggle>
                    <button
                        type="button"
                        class="flex items-center p-[6px] rounded-[6px] text-[12px] text-blue-600 font-semibold transition-all hover:bg-gray-100 dark:hover:bg-gray-950  focus:bg-gray-100 cursor-pointer"
                    >
                        @lang('admin::app.catalog.products.edit.types.configurable.mass-edit.select-variants')

                        <i class="icon-sort-down text-[24px] text-blue-600"></i>
                    </button>
                </x-slot:toggle>

                <!-- Dropdown Content -->
                <x-slot:content class="px-[0px] py-[15px]">
                    <template v-for="attribute in superAttributes">
                        <label
                            class="flex gap-[10px] items-center px-5 py-2 text-[14px] text-gray-600 dark:text-gray-300 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950  select-none"
                            :for="'attribute_' + attribute.id + '_option_' + option.id"
                            v-for="option in usedAttributeOptions(attribute)"
                        >
                            <div class="flex select-none">
                                <input
                                    type="checkbox"
                                    :id="'attribute_' + attribute.id + '_option_' + option.id"
                                    class="hidden peer"
                                    :checked="isAttributeOptionChecked(attribute, option)"
                                    @change="selectVariantsByAttributeOption(attribute, option)"
                                >

                                <label
                                    class="icon-uncheckbox text-[24px] peer-checked:icon-checked peer-checked:text-blue-600  cursor-pointer"
                                    :for="'attribute_' + attribute.id + '_option_' + option.id"
                                >
                                </label>
                            </div>

                            <div class="flex gap-[5px] items-center">
                                <span class="text-gray-800 dark:text-white">
                                    @{{ attribute.admin_name }}
                                </span>

                                <i class="icon-sort-right text-[18px]"></i>

                                @{{ option.admin_name }}
                            </div>
                        </label>
                    </template>
                </x-slot:content>
            </x-admin::dropdown>

            <!-- Actions Selector -->
            <x-admin::dropdown v-if="selectedVariants.length">
                <!-- Dropdown Toggler -->
                <x-slot:toggle>
                    <button
                        type="button"
                        class="flex items-center p-[6px] rounded-[6px] text-[12px] text-blue-600 font-semibold transition-all hover:bg-gray-100 dark:hover:bg-gray-950  focus:bg-gray-100 cursor-pointer"
                    >
                        @lang('admin::app.catalog.products.edit.types.configurable.mass-edit.select-action')

                        <i class="icon-sort-down text-[24px] text-blue-600"></i>
                    </button>
                </x-slot:toggle>

                <!-- Dropdown Content -->
                <x-slot:menu>
                    <x-admin::dropdown.menu.item
                        v-for="type in updateTypes"
                        @click="edit(type.key)"
                    >
                        @{{ type.title }}
                    </x-admin::dropdown.menu.item>
                </x-slot:menu>
            </x-admin::dropdown>

            <!-- Edit Drawer -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, update)">
                    <!-- Edit Drawer -->
                    <x-admin::drawer
                        ref="updateVariantsDrawer"
                        class="text-left"
                    >
                        <!-- Drawer Header -->
                        <x-slot:header>
                            <div class="flex justify-between items-center">
                                <p class="text-[20px] font-medium dark:text-white">
                                    @{{ updateTypes[selectedType].title }}
                                </p>

                                <button class="mr-[45px] primary-button">
                                    @lang('admin::app.catalog.products.edit.types.configurable.edit.save-btn')
                                </button>
                            </div>
                        </x-slot:header>

                        <!-- Drawer Content -->
                        <x-slot:content class="p-[16px]">
                            <!-- Mass Update -->
                            <x-admin::form
                                v-slot="{ meta, errors, handleSubmit }"
                                as="div"
                            >
                                <form @submit="handleSubmit($event, update)">
                                    <template v-if="selectedType == 'editPrices'">
                                        <div class="pb-[10px] border-b-[1px] dark:border-gray-800  ">
                                            <div class="flex gap-[10px] items-center">
                                                <x-admin::form.control-group class="flex-1 mb-0">
                                                    <x-admin::form.control-group.label>
                                                        @lang('admin::app.catalog.products.edit.types.configurable.mass-edit.apply-to-all-sku')
                                                    </x-admin::form.control-group.label>
                        

                                                    <div class="relative">
                                                        <span class="absolute ltr:left-[15px] rtl:right-[15px] top-[50%] -translate-y-[50%] text-gray-500">
                                                            {{ core()->currencySymbol(core()->getBaseCurrencyCode()) }}
                                                        </span>

                                                        <x-admin::form.control-group.control
                                                            type="text"
                                                            name="price"
                                                            class="ltr:pl-[30px] rtl:pr-[30px]"
                                                            ::rules="{required: true, decimal: true, min_value: 0}"
                                                            :label="trans('admin::app.catalog.products.edit.types.configurable.mass-edit.price')"
                                                        >
                                                        </x-admin::form.control-group.control>
                                                    </div>
                                                </x-admin::form.control-group>

                                                <button class="secondary-button mt-[15px]">
                                                    @lang('admin::app.catalog.products.edit.types.configurable.mass-edit.apply-to-all-btn')
                                                </button>
                                            </div>
                    
                                            <x-admin::form.control-group.error control-name="price"></x-admin::form.control-group.error>
                                        </div>
                                    </template>

                                    <template v-if="selectedType == 'editInventories'">
                                        <div class="pb-[10px] border-b-[1px] dark:border-gray-800  ">
                                            <div class="grid grid-cols-3 gap-[16px] mb-[10px]">
                                                <x-admin::form.control-group
                                                    class="mb-[0px]"
                                                    v-for='inventorySource in inventorySources'
                                                >
                                                    <x-admin::form.control-group.label>
                                                        @{{ inventorySource.name }}
                                                    </x-admin::form.control-group.label>

                                                    <v-field
                                                        type="text"
                                                        :name="'inventories[' + inventorySource.id + ']'"
                                                        class="flex w-full min-h-[39px] py-[6px] px-[12px] bg-white dark:bg-gray-900  border dark:border-gray-800   rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                                                        :class="[errors['inventories[' + inventorySource.id + ']'] ? 'border border-red-500' : '']"
                                                        rules="required|numeric|min:0"
                                                        :label="inventorySource.name"
                                                    >
                                                    </v-field>

                                                    <v-error-message
                                                        :name="'inventories[' + inventorySource.id + ']'"
                                                        v-slot="{ message }"
                                                    >
                                                        <p
                                                            class="mt-1 text-red-600 text-xs italic"
                                                            v-text="message"
                                                        >
                                                        </p>
                                                    </v-error-message>
                                                </x-admin::form.control-group>
                                            </div>

                                            <button class="secondary-button">
                                                @lang('admin::app.catalog.products.edit.types.configurable.mass-edit.apply-to-all-btn')
                                            </button>
                                        </div>
                                    </template>

                                    <template v-if="selectedType == 'addImages'">
                                        <div class="pb-[10px] border-b-[1px] dark:border-gray-800  ">
                                            <v-media-images
                                                name="images"
                                                class="mb-[10px]"
                                                v-bind:allow-multiple="true"
                                                :uploaded-images="updateTypes[selectedType].images"
                                            ></v-media-images>

                                            <button class="secondary-button">
                                                @lang('admin::app.catalog.products.edit.types.configurable.mass-edit.apply-to-all-btn')
                                            </button>
                                        </div>
                                    </template>
                                </form>
                            </x-admin::form>

                            <div
                                class="py-[16px] border-b-[1px] dark:border-gray-800   last:border-b-0"
                                :class="{'flex gap-[10px] justify-between items-center': selectedType == 'editPrices'}"
                                v-for="variant in selectedVariants"
                            >
                                <div class="text-[14px] text-gray-800">
                                    <span
                                        class="after:content-['_/_'] last:after:content-['']"
                                        v-for='(attribute, index) in superAttributes'
                                    >
                                        @{{ optionName(attribute, variant[attribute.code]) }}
                                    </span>
                                </div>

                                <template v-if="selectedType == 'editPrices'">
                                    <x-admin::form.control-group class="flex-1 mb-0 max-w-[115px]">
                                        <div class="relative">
                                            <span class="absolute ltr:left-[15px] rtl:right-[15px] top-[50%] -translate-y-[50%] text-gray-500">
                                                {{ core()->currencySymbol(core()->getBaseCurrencyCode()) }}
                                            </span>

                                            <v-field
                                                type="text"
                                                :name="'variants[' + variant.id + ']'"
                                                :value="variant.price"
                                                class="flex w-full min-h-[39px] py-[6px] ltr:pl-[30px] rtl:pr-[30px] bg-white dark:bg-gray-900  border dark:border-gray-800   rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                                                :class="[errors['variants[' + variant.id + ']'] ? 'border border-red-500' : '']"
                                                :rules="{required: true, decimal: true, min_value: 0}"
                                                label="@lang('admin::app.catalog.products.edit.types.configurable.mass-edit.price')"
                                            >
                                            </v-field>
                                        </div>

                                        <v-error-message
                                            :name="'variants[' + variant.id + ']'"
                                            v-slot="{ message }"
                                        >
                                            <p
                                                class="mt-1 text-red-600 text-xs italic"
                                                v-text="message"
                                            >
                                            </p>
                                        </v-error-message>
                                    </x-admin::form.control-group>
                                </template>

                                <template v-if="selectedType == 'editInventories'">
                                    <x-admin::form.control-group class="mt-[10px] mb-0">
                                        <div class="grid grid-cols-3 gap-[16px] mb-[10px]">
                                            <x-admin::form.control-group
                                                class="mb-[0px]"
                                                v-for='inventorySource in inventorySources'
                                            >
                                                <x-admin::form.control-group.label>
                                                    @{{ inventorySource.name }}
                                                </x-admin::form.control-group.label>

                                                <v-field
                                                    type="text"
                                                    :name="'variants[' + variant.id + '][' + inventorySource.id + ']'"
                                                    :value="variant.inventories[inventorySource.id]"
                                                    class="flex w-full min-h-[39px] py-[6px] px-[12px] bg-white dark:bg-gray-900  border dark:border-gray-800   rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                                                    :class="[errors['variants[' + variant.id + '][' + inventorySource.id + ']'] ? 'border border-red-500' : '']"
                                                    rules="required|numeric|min:0"
                                                    :label="inventorySource.name"
                                                >
                                                </v-field>

                                                <v-error-message
                                                    :name="'variants[' + variant.id + '][' + inventorySource.id + ']'"
                                                    v-slot="{ message }"
                                                >
                                                    <p
                                                        class="mt-1 text-red-600 text-xs italic"
                                                        v-text="message"
                                                    >
                                                    </p>
                                                </v-error-message>
                                            </x-admin::form.control-group>
                                        </div>
                                    </x-admin::form.control-group>
                                </template>
                                
                                <template v-if="selectedType == 'addImages'">
                                    <v-media-images
                                        name="images"
                                        class="mt-[10px]"
                                        v-bind:allow-multiple="true"
                                        :uploaded-images="variant.temp_images"
                                    ></v-media-images>
                                </template>
                            </div>
                        </x-slot:content>
                    </x-admin::drawer>
                </form>
            </x-admin::form>
        </div>
    </script>

    {{-- Variation Item Template --}}
    <script type="text/x-template" id="v-product-variation-item-template"> 
        <div class="flex gap-[10px] justify-between px-[16px] py-[24px] border-b-[1px] border-slate-300 dark:border-gray-800">

            <!-- Information -->
            <div class="flex gap-[10px]">
                <!-- Form Hidden Fields -->
                <input type="hidden" :name="'variants[' + variant.id + '][sku]'" :value="variant.sku"/>

                <input type="hidden" :name="'variants[' + variant.id + '][name]'" :value="variant.name"/>

                <input type="hidden" :name="'variants[' + variant.id + '][price]'" :value="variant.price"/>

                <input type="hidden" :name="'variants[' + variant.id + '][weight]'" :value="variant.weight"/>

                <input type="hidden" :name="'variants[' + variant.id + '][status]'" :value="variant.status"/>

                <template v-for="attribute in attributes">
                    <input type="hidden" :name="'variants[' + variant.id + '][' + attribute.code + ']'" :value="variant[attribute.code]"/>
                </template>

                <template v-for="inventorySource in inventorySources">
                    <input type="hidden" :name="'variants[' + variant.id + '][inventories][' + inventorySource.id + ']'" :value="variant.inventories[inventorySource.id]"/>
                </template>

                <template v-for="(image, index) in variant.images">
                    <input type="hidden" :name="'variants[' + variant.id + '][images][files][' + image.id + ']'" v-if="! image.is_new"/>

                    <input
                        type="file"
                        :name="'variants[' + variant.id + '][images][files][]'"
                        :id="$.uid + '_imageInput_' + index"
                        class="hidden"
                        accept="image/*"
                        :ref="$.uid + '_imageInput_' + index"
                    />
                </template>
                <!-- //Ends Form Hidden Fields -->

                <!-- Selection Checkbox -->
                <div class="select-none">
                    <input
                        type="checkbox"
                        :id="'variant_' + variant.id"
                        class="hidden peer"
                        v-model="variant.selected"
                    >

                    <label
                        class="icon-uncheckbox text-[24px] peer-checked:icon-checked peer-checked:text-blue-600  cursor-pointer"
                        :for="'variant_' + variant.id"
                    ></label>
                </div>

                <!-- Image -->
                <div
                    class="w-full h-[60px] max-w-[60px] max-h-[60px] relative rounded-[4px] overflow-hidden"
                    :class="{'border border-dashed border-gray-300 dark:border-gray-800 dark:invert dark:mix-blend-exclusion': ! variant.images.length}"
                >
                    <template v-if="! variant.images.length">
                        <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                    
                        <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                            @lang('admin::app.catalog.products.edit.types.configurable.image-placeholder')
                        </p>
                    </template>

                    <template v-else>
                        <img :src="variant.images[0].url">

                        <span
                            class="absolute bottom-[1px] ltr:left-[1px] rtl:right-[1px] text-[12px] font-bold text-white bg-darkPink rounded-full px-[6px]"
                            v-text="variant.images.length"
                        >
                        </span>
                    </template>
                </div>

                <!-- Details -->
                <div class="grid gap-[6px] place-content-start">
                    <p
                        class="text-[16x] text-gray-800 dark:text-white font-semibold"
                        v-text="variant.name ?? 'N/A'"
                    >
                    </p>

                    <p class="text-gray-600 dark:text-gray-300">
                        @{{ "@lang('admin::app.catalog.products.edit.types.configurable.sku')".replace(':sku', variant.sku) }}
                    </p>

                    <v-error-message
                        :name="'variants[' + variant.id + '].sku'"
                        v-slot="{ message }"
                    >
                        <p
                            class="mt-1 text-red-600 text-xs italic"
                            v-text="message"
                        >
                        </p>
                    </v-error-message>

                    <div class="flex gap-[6px] place-items-start">
                        <span
                            class="label-active"
                            v-if="isDefault"
                        >
                            Default
                        </span>

                        <p class="text-gray-600 dark:text-gray-300">
                            <span
                                class="after:content-[',_'] last:after:content-['']"
                                v-for='(attribute, index) in attributes'
                            >
                                @{{ attribute.admin_name + ': ' + optionName(attribute, variant[attribute.code]) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="grid gap-[4px] place-content-start text-right">
                <p class="text-gray-800 font-semibold dark:text-white">
                    @{{ $admin.formatPrice(variant.price) }}  
                </p>

                <p class="text-gray-800 font-semibold dark:text-white">
                    @{{ "@lang('admin::app.catalog.products.edit.types.configurable.qty')".replace(':qty', totalQty) }}
                </p>

                <div class="flex gap-[10px]">
                    <!-- Remove -->
                    <p
                        class="text-red-600 cursor-pointer transition-all hover:underline"
                        @click="remove"
                    >
                        @lang('admin::app.catalog.products.edit.types.configurable.delete-btn')
                    </p>
                    
                    <!-- Edit -->
                    <div>
                        <p
                            class="text-emerald-600 cursor-pointer transition-all hover:underline"
                            @click="$refs.editVariantDrawer.open()"
                        >
                            @lang('admin::app.catalog.products.edit.types.configurable.edit-btn')
                        </p>

                        <!-- Edit Drawer -->
                        <x-admin::form
                            v-slot="{ meta, errors, handleSubmit }"
                            as="div"
                        >
                            <form @submit="handleSubmit($event, update)">
                                <!-- Edit Drawer -->
                                <x-admin::drawer
                                    ref="editVariantDrawer"
                                    class="text-left"
                                >
                                    <!-- Drawer Header -->
                                    <x-slot:header>
                                        <div class="flex justify-between items-center">
                                            <p class="text-[20px] font-medium dark:text-white">
                                                @lang('admin::app.catalog.products.edit.types.configurable.edit.title')
                                            </p>

                                            <button class="mr-[45px] primary-button">
                                                @lang('admin::app.catalog.products.edit.types.configurable.edit.save-btn')
                                            </button>
                                        </div>
                                    </x-slot:header>

                                    <!-- Drawer Content -->

                                    <x-slot:content>
                                        <x-admin::form.control-group.control
                                            type="hidden"
                                            name="id"
                                            ::value="variant.id"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.catalog.products.edit.types.configurable.edit.name')
                                            </x-admin::form.control-group.label>
                
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="name"
                                                ::value="variant.name"
                                                rules="required"
                                                :label="trans('admin::app.catalog.products.edit.types.configurable.edit.name')"
                                            >
                                            </x-admin::form.control-group.control>
                
                                            <x-admin::form.control-group.error control-name="name"></x-admin::form.control-group.error>
                                        </x-admin::form.control-group>

                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.catalog.products.edit.types.configurable.edit.sku')
                                            </x-admin::form.control-group.label>
                
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="sku"
                                                ::value="variant.sku"
                                                rules="required"
                                                :label="trans('admin::app.catalog.products.edit.types.configurable.edit.sku')"
                                            >
                                            </x-admin::form.control-group.control>
                
                                            <x-admin::form.control-group.error control-name="sku"></x-admin::form.control-group.error>
                                        </x-admin::form.control-group>

                                        <div class="flex gap-[16px] mb-[10px]">
                                            <x-admin::form.control-group class="flex-1">
                                                <x-admin::form.control-group.label class="required">
                                                    @lang('admin::app.catalog.products.edit.types.configurable.edit.price')
                                                </x-admin::form.control-group.label>
                    
                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="price"
                                                    ::value="variant.price"
                                                    ::rules="{required: true, decimal: true, min_value: 0}"
                                                    :label="trans('admin::app.catalog.products.edit.types.configurable.edit.price')"
                                                >
                                                </x-admin::form.control-group.control>
                    
                                                <x-admin::form.control-group.error control-name="price"></x-admin::form.control-group.error>
                                            </x-admin::form.control-group>

                                            <x-admin::form.control-group class="flex-1">
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.catalog.products.edit.types.configurable.edit.status')
                                                </x-admin::form.control-group.label>
                    
                                                <x-admin::form.control-group.control
                                                    type="select"
                                                    name="status"
                                                    ::value="variant.status"
                                                    rules="required"
                                                    :label="trans('admin::app.catalog.products.edit.types.configurable.edit.status')"
                                                >
                                                    <option value="1">
                                                        @lang('admin::app.catalog.products.edit.types.configurable.edit.enabled')
                                                    </option>

                                                    <option value="0">
                                                        @lang('admin::app.catalog.products.edit.types.configurable.edit.disabled')
                                                    </option>
                                                </x-admin::form.control-group.control>
                    
                                                <x-admin::form.control-group.error control-name="status"></x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>

                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.catalog.products.edit.types.configurable.edit.weight')
                                            </x-admin::form.control-group.label>
                
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="weight"
                                                ::value="variant.weight"
                                                ::rules="{ required: true, regex: /^([0-9]*[1-9][0-9]*(\.[0-9]+)?|[0]+\.[0-9]*[1-9][0-9]*)$/ }"
                                                :label="trans('admin::app.catalog.products.edit.types.configurable.edit.weight')"
                                            >
                                            </x-admin::form.control-group.control>
                
                                            <x-admin::form.control-group.error control-name="weight"></x-admin::form.control-group.error>
                                        </x-admin::form.control-group>

                                        <!-- Inventories -->
                                        <div class="grid mt-[20px]">
                                            <p class="mb-[10px] text-gray-800 dark:text-white font-semibold">
                                                @lang('admin::app.catalog.products.edit.types.configurable.edit.quantities')
                                            </p>

                                            <div class="grid grid-cols-3 gap-[16px] mb-[10px]">
                                                <x-admin::form.control-group
                                                    class="mb-[0px]"
                                                    v-for='inventorySource in inventorySources'
                                                >
                                                    <x-admin::form.control-group.label>
                                                        @{{ inventorySource.name }}
                                                    </x-admin::form.control-group.label>

                                                    <v-field
                                                        type="text"
                                                        :name="'inventories[' + inventorySource.id + ']'"
                                                        v-model="variant.inventories[inventorySource.id]"
                                                        class="flex w-full min-h-[39px] py-[6px] px-[12px] bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                                                        :class="[errors['inventories[' + inventorySource.id + ']'] ? 'border border-red-500' : '']"
                                                        rules="numeric|min:0"
                                                        :label="inventorySource.name"
                                                    >
                                                    </v-field>

                                                    <v-error-message
                                                        :name="'inventories[' + inventorySource.id + ']'"
                                                        v-slot="{ message }"
                                                    >
                                                        <p
                                                            class="mt-1 text-red-600 text-xs italic"
                                                            v-text="message"
                                                        >
                                                        </p>
                                                    </v-error-message>
                                                </x-admin::form.control-group>
                                            </div>
                                        </div>

                                        <!-- Images -->
                                        <div class="mb-[10px]">
                                            <p class="mb-[10px] text-gray-800 dark:text-white font-semibold">
                                                @lang('admin::app.catalog.products.edit.types.configurable.edit.images')
                                            </p>

                                            <v-media-images
                                                name="images"
                                                v-bind:allow-multiple="true"
                                                :uploaded-images="variant.images"
                                            ></v-media-images>
                                        </div>

                                        <!-- Actions -->
                                        <div
                                            class="mt-[10px] text-[14px] text-gray-800 dark:text-white font-semibold"
                                            v-if="typeof variant.id !== 'string'"
                                        >
                                            @lang('admin::app.catalog.products.edit.types.configurable.edit.edit-info')

                                            <a
                                                :href="'{{ route('admin.catalog.products.edit', ':id') }}'.replace(':id', variant.id)" 
                                                class="inline-block text-blue-500 hover:text-blue-600 hover:underline"
                                                target="_blank"
                                            >
                                                @lang('admin::app.catalog.products.edit.types.configurable.edit.edit-link-title')
                                            </a>
                                        </div>
                                    </x-slot:content>
                                </x-admin::drawer>
                            </form>
                        </x-admin::form>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-product-variations', {
            template: '#v-product-variations-template',

            props: ['errors'],

            data () {
                return {
                    defaultId: parseInt('{{ $product->additional['default_variant_id'] ?? null }}'),

                    variants: @json($product->variants()->with(['attribute_family', 'images', 'inventories'])->get()),

                    superAttributes: @json($product->super_attributes()->with(['options', 'options.attribute', 'options.translations'])->get()),

                    selectedVariant: {
                        id: null,
                        name: '',
                        sku: '',
                        price: 0,
                        status: 1,
                        weight: 0,
                        inventories: {},
                        images: []
                    },
                }
            },

            methods: {
                addVariant(params, { resetForm }) {
                    let self = this;

                    let filteredVariants = this.variants.filter(function (variant) {
                        let matchCount = 0;

                        for (let key in params) {
                            if (variant[key] == params[key]) {
                                matchCount++;
                            }
                        }

                        return matchCount == self.superAttributes.length;
                    })

                    if (filteredVariants.length) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('admin::app.catalog.products.edit.types.configurable.create.variant-already-exists')" });

                        return;
                    }

                    const optionIds = Object.values(params);

                    this.variants.push(Object.assign({
                        id: 'variant_' + this.variants.length,
                        sku: '{{ $product->sku }}' + '-variant-' + optionIds.join('-'),
                        name: '',
                        price: 0,
                        status: 1,
                        weight: 0,
                        inventories: {},
                        images: []
                    }, params));

                    resetForm();

                    this.$refs.variantCreateModal.close();
                },

                removeVariant(variant) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.variants.splice(this.variants.indexOf(variant), 1);
                        },
                    });
                },
            }
        });

        app.component('v-product-variations-mass-action', {
            template: '#v-product-variations-mass-action-template',

            props: ['superAttributes', 'variants'],

            data: function () {
                return {
                    inventorySources: @json($inventorySources),

                    updateTypes: {
                        editPrices: {
                            key: 'editPrices',
                            title: "@lang('admin::app.catalog.products.edit.types.configurable.mass-edit.edit-prices')"
                        },

                        editInventories: {
                            key: 'editInventories',
                            title: "@lang('admin::app.catalog.products.edit.types.configurable.mass-edit.edit-inventories')"
                        },

                        addImages: {
                            key: 'addImages',
                            title: "@lang('admin::app.catalog.products.edit.types.configurable.mass-edit.add-images')",
                            images: []
                        },

                        removeImages: {
                            key: 'removeImages',
                            title: "@lang('admin::app.catalog.products.edit.types.configurable.mass-edit.remove-images')"
                        },

                        removeVariants: {
                            key: 'removeVariants',
                            title: "@lang('admin::app.catalog.products.edit.types.configurable.mass-edit.remove-variants')"
                        }
                    },

                    selectedType: ''
                }
            },

            computed: {
                selectedVariants() {
                    return this.variants.filter(function(variant) {
                        variant.temp_images = [];

                        return variant.selected;
                    });
                }
            },

            methods: {
                usedAttributeOptions(attribute) {
                    const options = [];

                    for (const option of attribute.options) {
                        if (this.variants.some(variant => variant[attribute.code] === option.id)) {
                            if (! options.includes(option)) {
                                options.push(option);
                            }
                        }
                    }

                    return options;
                },

                selectAll() {
                    let isSelected = this.selectedVariants.length <= 0;

                    this.variants.forEach(function (variant) {
                        variant.selected = isSelected;
                    });
                },

                selectVariantsByAttributeOption(attribute, option) {
                    let self = this;

                    let isAttributeOptionChecked = self.isAttributeOptionChecked(attribute, option);

                    this.variants.forEach(function (variant) {
                        if (variant[attribute.code] == option.id) {
                            variant.selected = ! isAttributeOptionChecked;
                        }
                    });
                },

                isAttributeOptionChecked(attribute, option) {
                    let variants = this.variants.filter(function (variant) {
                        return variant[attribute.code] == option.id;
                    });

                    if (! variants.length) {
                        return false;
                    }
                    
                    let isSelected = true;

                    variants.forEach(function (variant) {
                        if (! variant.selected) {
                            isSelected = false;
                        }
                    });

                    return isSelected;
                },

                edit(type) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.selectedType = type;

                            if (['editPrices', 'editInventories', 'addImages'].includes(type)) {
                                this.$refs.updateVariantsDrawer.open();
                            } else {
                                this[this.selectedType]();
                            }
                        }
                    });
                },

                update(params) {
                    this[this.selectedType](params);

                    this.$refs.updateVariantsDrawer.close();
                },

                editPrices(params) {
                    this.selectedVariants.forEach(function (variant) {
                        variant.price = params?.price ?? params.variants[variant.id];

                        variant.selected = false;
                    });
                },

                editInventories(params) {
                    this.selectedVariants.forEach(function (variant) {
                        variant.inventories = params?.inventories ?? params.variants[variant.id];

                        variant.selected = false;
                    });
                },

                addImages(params) {
                    let self = this;

                    this.selectedVariants.forEach(function (variant) {
                        if (self.updateTypes.addImages.images.length) {
                            variant.images = variant.images.concat(self.updateTypes.addImages.images);
                        } else {
                            variant.images = variant.images.concat(variant.temp_images);

                            variant.temp_images = [];
                        }

                        variant.selected = false;
                    });

                    this.updateTypes.addImages.images = [];
                },

                removeImages() {
                    this.selectedVariants.forEach(function (variant) {
                        variant.images = [];

                        variant.selected = false;
                    });
                },

                removeVariants() {
                    let self = this;

                    this.selectedVariants.forEach(function (variant) {
                        if (variant.selected) {
                            let index = self.variants.indexOf(variant);

                            self.variants.splice(index, 1);
                        }
                    });
                },

                optionName: function (attribute, optionId) {
                    return attribute.options.find(function (option) {
                        return option.id == optionId;
                    })?.admin_name;
                },
            }
        });

        app.component('v-product-variation-item', {
            template: '#v-product-variation-item-template',

            props: [
                'variant',
                'attributes',
                'errors',
            ],

            data() {
                return {
                    inventorySources: @json($inventorySources),
                }
            },

            created() {
                let inventories = {};
                
                if (Array.isArray(this.variant.inventories)) {
                    this.variant.inventories.forEach(function (inventory) {
                        inventories[inventory.inventory_source_id] = inventory.qty;
                    });

                    this.variant.inventories = inventories; 
                }
            },

            mounted() {
                if (typeof this.variant.id === 'string' || this.variant.id instanceof String) {
                    this.$refs.editVariantDrawer.open();
                }
            },

            computed: {
                isDefault() {
                    return this.variant.id == this.$parent.defaultId;
                },

                totalQty() {
                    let totalQty = 0;

                    for (let key in this.variant.inventories) {
                        totalQty += parseInt(this.variant.inventories[key]);
                    }

                    return totalQty;
                }
            },

            watch: {
                variant: {
                    handler: function(newValue) {
                        let self = this;

                        setTimeout(function() {
                            self.setFiles();
                        })
                    },
                    deep: true
                }
            },

            methods: {
                optionName: function (attribute, optionId) {
                    return attribute.options.find(function (option) {
                        return option.id == optionId;
                    })?.admin_name;
                },

                update(params) {
                    Object.assign(this.variant, params);

                    this.$refs.editVariantDrawer.close();
                },

                setFiles() {
                    let self = this;

                    this.variant.images.forEach(function (image, index) {
                        if (image.file instanceof File) {
                            image.is_new = 1;

                            const dataTransfer = new DataTransfer();

                            dataTransfer.items.add(image.file);

                            self.$refs[self.$.uid + '_imageInput_' + index][0].files = dataTransfer.files;
                        } else {
                            image.is_new = 0;
                        }
                    });
                },

                remove: function () {
                    this.$emit('onRemoved', this.variant);
                },
            }
        });
    </script>
@endPushOnce