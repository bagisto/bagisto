@if ($product->getTypeInstance()->isCustomizable())
    @php
        $options = $product->customizable_options()->with([
            'product',
            'customizable_option_prices',
        ])->get();
    @endphp

    {!! view_render_event('bagisto.admin.catalog.product.edit.form.types.simple.customizable-options.before', ['product' => $product]) !!}

    <v-customizable-options :errors="errors"></v-customizable-options>

    {!! view_render_event('bagisto.admin.catalog.product.edit.form.types.simple.customizable-options.after', ['product' => $product]) !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-customizable-options-template"
        >
            <div class="box-shadow relative rounded bg-white dark:bg-gray-900">
                <!-- Option Panel Header -->
                <div class="p-4 flex flex-col mb-2.5">
                    <div class="flex justify-between gap-5">
                        <!-- Option Title & Option Info -->
                        <div class="flex flex-col gap-2">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.catalog.products.edit.types.simple.customizable-options.title')
                            </p>

                            <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                                @lang('admin::app.catalog.products.edit.types.simple.customizable-options.info')
                            </p>
                        </div>

                        <!-- Add Option Button -->
                        <div class="flex items-center gap-x-1">
                            <div
                                class="secondary-button"
                                @click="resetForm(); $refs.updateCreateOptionModal.open()"
                            >
                                @lang('admin::app.catalog.products.edit.types.simple.customizable-options.add-btn')
                            </div>
                        </div>
                    </div>

                    <!-- Backend Validations -->
                    <x-admin::form.control-group.error control-name="customizable_options" />
                </div>

                <!-- Option Panel Content -->
                <div
                    class="grid"
                    v-if="options.length"
                >
                    <draggable
                        ghost-class="draggable-ghost"
                        v-bind="{ animation: 200 }"
                        handle=".icon-drag"
                        :list="options"
                        item-key="id"
                    >
                        <template #item="{ element, index }">
                            <div>
                                <!--
                                    Hidden Attributes:

                                    All hidden attributes are used to store form data for this component only. They are kept in a single
                                    place to avoid confusion.
                                -->
                                <input
                                    type="hidden"
                                    :name="'customizable_options[' + element.id + '][{{$currentLocale->code}}][label]'"
                                    :value="element.label"
                                />

                                <input
                                    type="hidden"
                                    :name="'customizable_options[' + element.id + '][type]'"
                                    :value="element.type"
                                />

                                <input
                                    type="hidden"
                                    :name="'customizable_options[' + element.id + '][is_required]'"
                                    :value="element.is_required"
                                />

                                <input
                                    type="hidden"
                                    :name="'customizable_options[' + element.id + '][sort_order]'"
                                    :value="index"
                                />

                                <input
                                    type="hidden"
                                    :name="'customizable_options[' + element.id + '][max_characters]'"
                                    :value="element.max_characters"
                                    v-if="! canHaveMultiplePrices(element.type)"
                                />

                                <input
                                    type="hidden"
                                    :name="'customizable_options[' + element.id + '][supported_file_extensions]'"
                                    :value="element.supported_file_extensions"
                                    v-if="! canHaveMultiplePrices(element.type)"
                                />

                                <input
                                    type="hidden"
                                    :name="'customizable_options[' + element.id + '][prices][' + element.price_id + '][price]'"
                                    :value="element.price"
                                    v-if="! canHaveMultiplePrices(element.type)"
                                />

                                <!--
                                    This block supports the following types, which can have only a single price:
                                    - text
                                    - textarea
                                    - date
                                    - datetime
                                    - time
                                    - file
                                -->
                                <div
                                    class="mb-2.5 flex justify-between gap-5 p-4"
                                    v-if="! canHaveMultiplePrices(element.type)"
                                >
                                    <!-- Option Information -->
                                    <div class="flex gap-2.5">
                                        <i class="icon-drag cursor-grab text-xl transition-all hover:text-gray-700 dark:text-gray-300"></i>

                                        <p
                                            class="text-base font-semibold text-gray-800 dark:text-white"
                                            :class="{'required': element.is_required == 1}"
                                        >
                                            @{{ (index + 1) + '. ' + element.label + ' - ' + types[element.type].title }}
                                        </p>
                                    </div>

                                    <!-- Option Action Buttons -->
                                    <div class="grid place-content-start gap-1 ltr:text-right rtl:text-left">
                                        <p class="font-semibold text-gray-800 dark:text-white">
                                            @{{ $admin.formatPrice(element.price) }}
                                        </p>

                                        <div class="flex gap-2">
                                            <!-- Edit Option -->
                                            <p
                                                class="cursor-pointer text-blue-600 transition-all hover:underline"
                                                @click="selectedOption = element; $refs.updateCreateOptionModal.open()"
                                            >
                                                @lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.edit-btn')
                                            </p>

                                            <!-- Remove Option -->
                                            <p
                                                class="cursor-pointer text-red-600 transition-all hover:underline"
                                                @click="removeOption(element)"
                                            >
                                                @lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.delete-btn')
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!--
                                    Customizable Option Item Component:

                                    This component is used to render customizable option items. It supports the following four types of options:
                                    - select
                                    - radio
                                    - multiselect
                                    - checkbox

                                    For all other types, the option items are rendered in the parent component. This component only handles the
                                    display and CRUD operations; all form handling is done in the parent component. The form parameters are
                                    prepared in the parent component.
                                -->
                                <v-customizable-option-item
                                    :key="index"
                                    :title="(index + 1) + '. ' + element.label + ' - ' + types[element.type].title"
                                    :option="element"
                                    @updateOption="updateOption($event)"
                                    @removeOption="removeOption($event)"
                                    v-else
                                >
                                </v-customizable-option-item>
                            </div>
                        </template>
                    </draggable>
                </div>

                <!-- For Empty Option -->
                <div
                    class="grid justify-center justify-items-center gap-3.5 px-2.5 py-10"
                    v-else
                >
                    <!-- Placeholder Image -->
                    <img
                        src="{{ bagisto_asset('images/icon-options.svg') }}"
                        class="h-20 w-20 rounded border border-dashed dark:border-gray-800 dark:mix-blend-exclusion dark:invert"
                    />

                    <!-- Add Information -->
                    <div class="flex flex-col items-center gap-1.5">
                        <p class="text-base font-semibold text-gray-400">
                            @lang('admin::app.catalog.products.edit.types.simple.customizable-options.empty-title')
                        </p>

                        <p class="text-gray-400">
                            @lang('admin::app.catalog.products.edit.types.simple.customizable-options.empty-info')
                        </p>
                    </div>

                    <!-- Update Create Option Item Modal -->
                    <div
                        class="secondary-button text-sm"
                        @click="resetForm(); $refs.updateCreateOptionModal.open()"
                    >
                        @lang('admin::app.catalog.products.edit.types.simple.customizable-options.add-btn')
                    </div>
                </div>

                <!-- Add Option Form Modal -->
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, updateOrCreate)">
                        <x-admin::modal ref="updateCreateOptionModal">
                            <!-- Option Form Modal Header -->
                            <x-slot:header>
                                <p class="text-lg font-bold text-gray-800 dark:text-white">
                                    @lang('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.title')
                                </p>
                            </x-slot>

                            <!-- Option Form Modal Content -->
                            <x-slot:content>
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="label"
                                        rules="required"
                                        ::value="selectedOption.label"
                                        :label="trans('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.name')"
                                    />

                                    <x-admin::form.control-group.error control-name="label" />
                                </x-admin::form.control-group>

                                <div class="flex gap-4">
                                    <x-admin::form.control-group class="flex-1">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.type')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="type"
                                            rules="required"
                                            v-model="selectedOption.type"
                                            :label="trans('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.type')"
                                        >
                                            <option
                                                :value="type.key"
                                                :key="key"
                                                v-for="(type, key) in types"
                                                v-text="type.title"
                                            >
                                            </option>
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error control-name="type" />
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group class="flex-1">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.is-required')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="is_required"
                                            rules="required"
                                            ::value="selectedOption.is_required"
                                            :label="trans('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.is-required')"
                                        >
                                            <option value="1">
                                                @lang('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.yes')
                                            </option>

                                            <option value="0">
                                                @lang('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.no')
                                            </option>
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error control-name="is_required" />
                                    </x-admin::form.control-group>
                                </div>

                                <template v-if="! canHaveMultiplePrices(selectedOption.type)">
                                    <x-admin::form.control-group v-if="['text', 'textarea'].includes(selectedOption.type)">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.max-characters')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="max_characters"
                                            rules="required|numeric|min_value:1"
                                            ::value="selectedOption.max_characters"
                                            :label="trans('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.max-characters')"
                                        />

                                        <x-admin::form.control-group.error control-name="max_characters" />
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group v-else-if="selectedOption.type == 'file'">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.supported-file-extensions')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="supported_file_extensions"
                                            rules="required"
                                            ::value="selectedOption.supported_file_extensions"
                                            :label="trans('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.supported-file-extensions')"
                                        />

                                        <x-admin::form.control-group.error control-name="supported_file_extensions" />
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.price')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="price"
                                            name="price"
                                            rules="required|decimal|min_value:0"
                                            ::value="selectedOption.price"
                                            :label="trans('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.price')"
                                        />

                                        <x-admin::form.control-group.error control-name="price" />
                                    </x-admin::form.control-group>
                                </template>
                            </x-slot>

                            <!-- Option Form Modal Footer -->
                            <x-slot:footer>
                                <!-- Save Button -->
                                <x-admin::button
                                    button-type="button"
                                    class="primary-button"
                                    :title="trans('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.save-btn')"
                                />
                            </x-slot>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script
            type="text/x-template"
            id="v-customizable-option-item-template"
        >
            <!-- Panel -->
            <div>
                <!-- Panel Header -->
                <div class="mb-2.5 flex justify-between gap-5 p-4">
                    <!-- Option Information -->
                    <div class="flex gap-2.5">
                        <i class="icon-drag cursor-grab text-xl transition-all hover:text-gray-700 dark:text-gray-300"></i>

                        <p
                            class="text-base font-semibold text-gray-800 dark:text-white"
                            :class="{'required': option.is_required == 1}"
                        >
                            @{{ title }}
                        </p>
                    </div>

                    <!-- Option Action Buttons -->
                    <div class="flex items-center gap-x-5">
                        <!-- Open Add Option Item Modal -->
                        <p
                            class="cursor-pointer text-blue-600 transition-all hover:underline"
                            @click="$refs.updateCreateOptionItemModal.open()"
                        >
                            @lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.add-btn')
                        </p>

                        <!-- Edit Option -->
                        <p
                            class="cursor-pointer text-blue-600 transition-all hover:underline"
                            @click="updateOption"
                        >
                            @lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.edit-btn')
                        </p>

                        <!-- Remove Option -->
                        <p
                            class="cursor-pointer text-red-600 transition-all hover:underline"
                            @click="removeOption"
                        >
                            @lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.delete-btn')
                        </p>
                    </div>
                </div>

                <!-- Option Item Panel Content -->
                <div
                    class="grid"
                    v-if="optionItems.length"
                >
                    <!-- Option Item Draggable Items -->
                    <draggable
                        ghost-class="draggable-ghost"
                        v-bind="{animation: 200}"
                        handle=".icon-drag"
                        :list="optionItems"
                        item-key="id"
                        @end="updateOption(false)"
                    >
                        <template #item="{ element, index }">
                            <div class="flex justify-between gap-2.5 border-b border-slate-300 p-4 dark:border-gray-800">
                                <!--
                                    Hidden Attributes:

                                    All hidden attributes are used to store form data for this component only. They are kept in a single
                                    place to avoid confusion.
                                -->
                                <input
                                    type="hidden"
                                    :name="'customizable_options[' + option.id + '][prices][' + element.id + '][label]'"
                                    :value="element.label"
                                />

                                <input
                                    type="hidden"
                                    :name="'customizable_options[' + option.id + '][prices][' + element.id + '][price]'"
                                    :value="element.price"
                                />

                                <input
                                    type="hidden"
                                    :name="'customizable_options[' + option.id + '][prices][' + element.id + '][sort_order]'"
                                    :value="index"
                                />

                                <!-- Option Item Information -->
                                <div class="flex gap-2.5">
                                    <!-- Option Item Drag Icon -->
                                    <div>
                                        <i class="icon-drag cursor-grab text-xl transition-all hover:text-gray-700 dark:text-gray-300"></i>
                                    </div>

                                    <!-- Option Item Details -->
                                    <div class="grid place-content-start gap-1.5">
                                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                                            @{{ element.label }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Option Item Actions -->
                                <div class="grid place-content-start gap-1 ltr:text-right rtl:text-left">
                                    <!-- Option Item Price -->
                                    <p class="font-semibold text-gray-800 dark:text-white">
                                        @{{ $admin.formatPrice(element.price) }}
                                    </p>

                                    <div class="flex gap-2">
                                        <!-- Edit Option Item -->
                                        <p
                                            class="cursor-pointer text-blue-600 transition-all hover:underline"
                                            @click="selectedOptionItem = element; $refs.updateCreateOptionItemModal.open()"
                                        >
                                            @lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.edit-btn')
                                        </p>

                                        <!-- Remove Option Item -->
                                        <p
                                            class="cursor-pointer text-red-600 transition-all hover:underline"
                                            @click="removeOptionItem(element)"
                                        >
                                            @lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.delete-btn')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </draggable>
                </div>

                <!-- For Empty Option Item -->
                <div
                    class="grid justify-center justify-items-center gap-3.5 px-2.5 py-10"
                    v-else
                >
                    <!-- Placeholder Image -->
                    <img
                        src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                        class="h-20 w-20 dark:mix-blend-exclusion dark:invert"
                    />

                    <!-- Add Information -->
                    <div class="flex flex-col items-center gap-1.5">
                        <p class="text-base font-semibold text-gray-400">
                            @lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.empty-title')
                        </p>

                        <p class="text-gray-400">
                            @lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.empty-info')
                        </p>
                    </div>

                    <div
                        class="secondary-button text-sm"
                        @click="$refs.updateCreateOptionItemModal.open()"
                    >
                        @lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.add-btn')
                    </div>
                </div>
            </div>

            <!-- Add Option Item Form Modal -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form
                    @submit="handleSubmit($event, updateOrCreateOptionItem)"
                >
                    <x-admin::modal ref="updateCreateOptionItemModal">
                        <!-- Option Item Modal Header -->
                        <x-slot:header>
                            <p class="text-lg font-bold text-gray-800 dark:text-white">
                                @lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.items.update-create.title')
                            </p>
                        </x-slot>

                        <!-- Option Item Modal Content -->
                        <x-slot:content>
                            <div class="flex gap-2">
                                <!-- Option Item Label -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.items.update-create.label')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="label"
                                        ::value="selectedOptionItem.label"
                                        rules="required"
                                        :label="trans('admin::app.catalog.products.edit.types.simple.customizable-options.option.items.update-create.label')"
                                    />

                                    <x-admin::form.control-group.error control-name="label" />
                                </x-admin::form.control-group>

                                <!-- Option Item Price -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.items.update-create.price')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="price"
                                        name="price"
                                        ::value="selectedOptionItem.price"
                                        rules="required|decimal|min_value:0"
                                        :label="trans('admin::app.catalog.products.edit.types.simple.customizable-options.option.items.update-create.price')"
                                    />

                                    <x-admin::form.control-group.error control-name="price" />
                                </x-admin::form.control-group>
                            </div>
                        </x-slot>

                        <!-- Option Item Modal Footer -->
                        <x-slot:footer>
                            <!-- Save Button -->
                            <x-admin::button
                                button-type="button"
                                class="primary-button"
                                :title="trans('admin::app.catalog.products.edit.types.simple.customizable-options.option.items.update-create.save-btn')"
                            />
                        </x-slot>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-customizable-options', {
                template: '#v-customizable-options-template',

                props: ['errors'],

                data() {
                    return {
                        types: {
                            text: {
                                key: 'text',
                                canHaveMultiplePrices: false,
                                title: "@lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.types.text.title')",
                            },

                            textarea: {
                                key: 'textarea',
                                canHaveMultiplePrices: false,
                                title: "@lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.types.textarea.title')",
                            },

                            checkbox: {
                                key: 'checkbox',
                                canHaveMultiplePrices: true,
                                title: "@lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.types.checkbox.title')",
                            },

                            radio: {
                                key: 'radio',
                                canHaveMultiplePrices: true,
                                title: "@lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.types.radio.title')",
                            },

                            select: {
                                key: 'select',
                                canHaveMultiplePrices: true,
                                title: "@lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.types.select.title')",
                            },

                            multiselect: {
                                key: 'multiselect',
                                canHaveMultiplePrices: true,
                                title: "@lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.types.multiselect.title')",
                            },

                            date: {
                                key: 'date',
                                canHaveMultiplePrices: false,
                                title: "@lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.types.date.title')",
                            },

                            datetime: {
                                key: 'datetime',
                                canHaveMultiplePrices: false,
                                title: "@lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.types.datetime.title')",
                            },

                            time: {
                                key: 'time',
                                canHaveMultiplePrices: false,
                                title: "@lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.types.time.title')",
                            },

                            file: {
                                key: 'file',
                                canHaveMultiplePrices: false,
                                title: "@lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.types.file.title')",
                            },
                        },

                        options: @json($options),

                        selectedOption: {
                            label: '',
                            type: 'select',
                            is_required: 1,
                            max_characters: null,
                            supported_file_extensions: null,
                            price: 0,
                        },
                    };
                },

                mounted() {
                    this.options = this.options.map((option) => {
                        if (! this.canHaveMultiplePrices(option.type)) {
                            return {
                                id: option.id,
                                label: option.label,
                                type: option.type,
                                is_required: option.is_required,
                                max_characters: option.max_characters,
                                supported_file_extensions: option.supported_file_extensions,
                                price_id: option.customizable_option_prices[0].id,
                                price: option.customizable_option_prices[0].price,
                                customizable_option_prices: option.customizable_option_prices,
                            };
                        }

                        return {
                            id: option.id,
                            label: option.label,
                            type: option.type,
                            is_required: option.is_required,
                            max_characters: option.max_characters,
                            supported_file_extensions: option.supported_file_extensions,
                            price: 0,
                            customizable_option_prices: option.customizable_option_prices,
                        };
                    });
                },

                methods: {
                    updateOrCreate(params) {
                        if (this.selectedOption.id == undefined) {
                            params.id = 'option_' + this.options.length;

                            if (! this.canHaveMultiplePrices(this.selectedOption.type)) {
                                params.price_id = 'price_0';
                            }

                            this.options.push(params);
                        } else {
                            params.id = this.selectedOption.id;

                            const indexToUpdate = this.options.findIndex(option => option.id === this.selectedOption.id);

                            this.options[indexToUpdate] = {
                                ...this.options[indexToUpdate],
                                ...params,
                            };
                        }

                        this.resetForm();

                        this.$refs.updateCreateOptionModal.close();
                    },

                    updateOption($event) {
                        this.selectedOption = $event;

                        if (! $event.updateModal) {
                            const indexToUpdate = this.options.findIndex(option => option.id === this.selectedOption.id);

                            this.options[indexToUpdate] = {
                                ...this.selectedOption,
                                customizable_option_prices: this.selectedOption.customizable_option_prices,
                            };

                            return;
                        }

                        this.$refs.updateCreateOptionModal.open();
                    },

                    removeOption(option) {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                let index = this.options.indexOf(option);

                                this.options.splice(index, 1);
                            }
                        });
                    },

                    resetForm() {
                        this.selectedOption = {
                            label: '',
                            type: 'select',
                            is_required: 1,
                            price: 0,
                        };
                    },

                    canHaveMultiplePrices(type) {
                        return this.types[type].canHaveMultiplePrices;
                    },
                },
            });

            app.component('v-customizable-option-item', {
                template: '#v-customizable-option-item-template',

                emits: ['updateOption', 'removeOption'],

                props: ['title', 'option'],

                data() {
                    return {
                        optionItems: [],

                        selectedOptionItem: {
                            label: '',
                            price: 0,
                        },
                    };
                },

                watch: {
                    option: {
                        handler: function (updatedOption) {
                            if (! updatedOption.customizable_option_prices) {
                                return;
                            }

                            this.optionItems = updatedOption.customizable_option_prices.map(optionItem => {
                                return {
                                    id: optionItem.id,
                                    label: optionItem.label,
                                    price: optionItem.price,
                                };
                            });
                        },

                        deep: true,

                        immediate: true,
                    },
                },

                methods: {
                    updateOption(updateModal = true) {
                        this.$emit('updateOption', {
                            ...this.option,
                            customizable_option_prices: this.optionItems,
                            updateModal,
                        });
                    },

                    removeOption() {
                        this.$emit('removeOption', this.option);
                    },

                    updateOrCreateOptionItem(params) {
                        if (this.selectedOptionItem.id == undefined) {
                            params.id = 'price_' + this.optionItems.length;

                            this.optionItems.push(params);
                        } else {
                            const indexToUpdate = this.optionItems.findIndex(optionItem => optionItem.id === this.selectedOptionItem.id);

                            this.optionItems[indexToUpdate] = {
                                ...this.optionItems[indexToUpdate],
                                ...params,
                            };
                        }

                        this.$emit('updateOption', {
                            ...this.option,
                            customizable_option_prices: this.optionItems,
                            updateModal: false,
                        });

                        this.resetForm();

                        this.$refs.updateCreateOptionItemModal.close();
                    },

                    removeOptionItem(selectedOptionItem) {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                this.optionItems = this.optionItems.filter(optionItem => optionItem.id != selectedOptionItem.id);

                                this.$emit('updateOption', {
                                    ...this.option,
                                    customizable_option_prices: this.optionItems,
                                    updateModal: false,
                                });
                            }
                        });
                    },

                    resetForm() {
                        this.selectedOptionItem = {
                            label: '',
                            price: 0,
                        };
                    },
                },
            });
        </script>
    @endPushOnce
@endif
