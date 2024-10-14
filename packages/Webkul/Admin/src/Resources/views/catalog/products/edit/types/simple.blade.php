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
            <div class="mb-2.5 flex justify-between gap-5 p-4">
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
                            <!-- Customizable Option Component -->
                            <v-customizable-option-item
                                :key="index"
                                :index="index"
                                :option="element"
                                :errors="errors"
                                @updateOption="selectedOption = $event; $refs.updateCreateOptionModal.open()"
                                @removeOption="removeOption($event)"
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
                    <!-- Customer Create Modal -->
                    <x-admin::modal ref="updateCreateOptionModal">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p class="text-lg font-bold text-gray-800 dark:text-white">
                                @lang('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.title')
                            </p>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="label"
                                    rules="required"
                                    v-model="selectedOption.label"
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
                                        <option value="select">
                                            @lang('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.select')
                                        </option>

                                        <option value="radio">
                                            @lang('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.radio')
                                        </option>

                                        <option value="checkbox">
                                            @lang('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.checkbox')
                                        </option>

                                        <option value="multiselect">
                                            @lang('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.multiselect')
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
                                        v-model="selectedOption.is_required"
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
                        </x-slot>

                        <!-- Modal Footer -->
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
            <!-- Hidden Inputs -->
            <input
                type="hidden"
                :name="'customizable_options[' + option.id + '][{{$currentLocale->code}}][label]'"
                :value="option.label"
            />

            <input
                type="hidden"
                :name="'customizable_options[' + option.id + '][type]'"
                :value="option.type"
            />

            <input
                type="hidden"
                :name="'customizable_options[' + option.id + '][is_required]'"
                :value="option.is_required"
            />

            <input
                type="hidden"
                :name="'customizable_options[' + option.id + '][sort_order]'"
                :value="index"
            />

            <!-- Panel Header -->
            <div class="mb-2.5 flex justify-between gap-5 p-4">
                <!-- Option Information -->
                <div class="flex gap-2.5">
                    <i class="icon-drag cursor-grab text-xl transition-all hover:text-gray-700 dark:text-gray-300"></i>

                    <p
                        class="text-base font-semibold text-gray-800 dark:text-white"
                        :class="{'required': option.is_required == 1}"
                    >
                        @{{ (index + 1) + '. ' + option.label + ' - ' + types[option.type].title }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-x-5">
                    <!-- Open Add Option Item Modal -->
                    <p
                        class="cursor-pointer font-semibold text-blue-600 transition-all hover:underline"
                        @click="$refs.updateCreateOptionItemModal.open()"
                    >
                        @lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.add-btn')
                    </p>

                    <!-- Edit Option -->
                    <p
                        class="cursor-pointer font-semibold text-blue-600 transition-all hover:underline"
                        @click="updateOption"
                    >
                        @lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.edit-btn')
                    </p>

                    <!-- Remove Option -->
                    <p
                        class="cursor-pointer font-semibold text-red-600 transition-all hover:underline"
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
                >
                    <template #item="{ element, index }">
                        <div class="flex justify-between gap-2.5 border-b border-slate-300 p-4 dark:border-gray-800">
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

                                    <!-- Option Item Label Hidden Input -->
                                    <input
                                        type="hidden"
                                        :name="'customizable_options[' + option.id + '][prices][' + element.id + '][label]'"
                                        :value="element.label"
                                    />
                                </div>
                            </div>

                            <!-- Option Item Actions -->
                            <div class="grid place-content-start gap-1 ltr:text-right rtl:text-left">
                                <!-- Option Item Price -->
                                <p class="font-semibold text-gray-800 dark:text-white">
                                    @{{ $admin.formatPrice(element.price) }}
                                </p>

                                <!-- Option Item Price Hidden Input -->
                                <input
                                    type="hidden"
                                    :name="'customizable_options[' + option.id + '][prices][' + element.id + '][price]'"
                                    :value="element.price"
                                />

                                <!-- Option Item Sort Order Hidden Input -->
                                <input
                                    type="hidden"
                                    :name="'customizable_options[' + option.id + '][prices][' + element.id + '][sort_order]'"
                                    :value="index"
                                />

                                <div class="flex gap-2">
                                    <!-- Edit Option -->
                                    <p
                                        class="cursor-pointer font-semibold text-blue-600 transition-all hover:underline"
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
                            @lang('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.title')
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
                                    v-model="selectedOptionItem.label"
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
                                    v-model="selectedOptionItem.price"
                                    rules="required"
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
                            :title="trans('admin::app.catalog.products.edit.types.simple.customizable-options.update-create.save-btn')"
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
                    options: @json($options),

                    selectedOption: {
                        label: '',
                        type: 'select',
                        is_required: 1,
                        customizable_option_prices: []
                    }
                }
            },

            methods: {
                updateOrCreate(params) {
                    if (this.selectedOption.id == undefined) {
                        params.id = 'option_' + this.options.length;

                        params.customizable_option_prices = [];

                        this.options.push(params);
                    } else {
                        const indexToUpdate = this.options.findIndex(option => option.id === this.selectedOption.id);

                        this.options[indexToUpdate] = this.selectedOption;
                    }

                    this.resetForm();

                    this.$refs.updateCreateOptionModal.close();
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
                        customizable_option_prices: []
                    };
                },
            }
        });

        app.component('v-customizable-option-item', {
            template: '#v-customizable-option-item-template',

            emits: ['updateOption', 'removeOption'],

            props: ['index', 'option', 'errors'],

            data() {
                return {
                    types: {
                        select: {
                            key: 'select',
                            title: "@lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.types.select.title')",
                            info: "@lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.types.select.info')"
                        },

                        radio: {
                            key: 'radio',
                            title: "@lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.types.radio.title')",
                            info: "@lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.types.radio.info')"
                        },

                        multiselect: {
                            key: 'multiselect',
                            title: "@lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.types.multiselect.title')",
                            info: "@lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.types.multiselect.info')"
                        },

                        checkbox: {
                            key: 'checkbox',
                            title: "@lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.types.checkbox.title')",
                            info: "@lang('admin::app.catalog.products.edit.types.simple.customizable-options.option.types.checkbox.info')"
                        }
                    },

                    optionItems: [],

                    selectedOptionItem: {
                        label: '',
                        price: 0,
                    }
                }
            },

            mounted() {
                this.optionItems = this.option.customizable_option_prices.map(optionItem => {
                    return {
                        id: optionItem.id,
                        label: optionItem.label,
                        price: optionItem.price,
                    };
                });
            },

            methods: {
                updateOption() {
                    this.$emit('updateOption', this.option);
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

                        this.optionItems[indexToUpdate] = this.selectedOptionItem;
                    }

                    this.resetForm();

                    this.$refs.updateCreateOptionItemModal.close();
                },

                removeOptionItem(selectedOptionItem) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.optionItems = this.optionItems.filter(optionItem => optionItem.id != selectedOptionItem.id);
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
