@props([
    'id',
    'name',
    'rules',
    'value',
    'label',
    'options',
    'selected',
])

<v-searchable-dropdown
    id="{{ $id }}"
    name="{{ $name }}"
    rules="{{ $rules }}"
    value="{{ $value }}"
    label="{{ $label }}"
    options="{{ $options->toJson() }}"
    selected="{{ $selected }}"
    :errors="errors"
>
</v-searchable-dropdown>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-searchable-dropdown-template"
    >
        <div>
            <v-field
                type="hidden"
                :name="name"
                :rules="rules"
                v-model="selectedOption.id"
            ></v-field>

            <x-admin::dropdown
                ::close-on-click="false"
                ref="dropdown"
            >
                <!-- Dropdown Toggler -->
                <x-slot:toggle>
                    <div class="flex flex-col w-full">
                        <button
                            type="button"
                            class="inline-flex w-full cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border dark:border-gray-800 bg-white dark:bg-gray-900 px-2.5 py-1.5 text-center leading-6 text-gray-600 dark:text-gray-300 transition-all marker:shadow hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400"
                            :class="[errors[name] ? 'border !border-red-600 hover:border-red-600' : '']"
                        >
                            <span
                                class="text-sm text-gray-600 dark:text-gray-400"
                                v-text="selectedOption.label"
                            >
                            </span>

                            <span class="icon-sort-down text-2xl text-black"></span>
                        </button>
                    </div>
                </x-slot>

                <!-- Dropdown Content -->
                <x-slot:menu class="!py-2">
                    <div class="relative">
                        <div class="relative rounded">
                            <ul class="list-reset">
                                <li class="p-2">
                                    <input
                                        class="block w-full rounded-md border dark:border-gray-800 bg-white dark:bg-gray-900 px-2 py-1.5 text-sm leading-6 text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400"
                                        @keyup="lookUp($event)"
                                    >
                                </li>

                                <ul class="p-2 max-h-40 overflow-y-auto">
                                    <li v-if="! getOptions.length">
                                        <p
                                            class="block p-2 text-gray-600 dark:text-gray-300"
                                            v-text="'@lang('admin::app.components.datagrid.filters.dropdown.searchable.no-results')'"
                                        >
                                        </p>
                                    </li>

                                    <li
                                        v-for="option in getOptions"
                                        v-else
                                    >
                                        <p
                                            class="text-sm text-gray-600 dark:text-gray-300 p-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950"
                                            v-text="option.label"
                                            @click="selectOption(option)"
                                        >
                                        </p>
                                    </li>
                                </ul>
                            </ul>
                        </div>
                    </div>
                </x-slot>
            </x-admin::dropdown>
        </div>
    </script>

    <script type="module">
        app.component('v-searchable-dropdown',  {
            template: '#v-searchable-dropdown-template',

            props: {
                id: {
                    type: String,
                    required: true,
                },
                name: {
                    type: String,
                    required: true,
                },
                rules: {
                    type: String,
                    required: true,
                },
                value: {
                    type: String,
                    required: true,
                },
                label: {
                    type: String,
                    required: true,
                },
                options: {
                    type: String,
                    required: true,
                },
                selected: {
                    type: String,
                    required: true,
                },
                errors: {
                    type: Object,
                }
            },

            data() {
                return {
                    selectedOption: this.value,

                    originalOptions: JSON.parse(this.options),

                    getOptions: [],
                };
            },

            mounted() {
                this.getOptions = this.originalOptions;
            },

            methods: {
                lookUp(event) {
                    const query = event.target.value.toLowerCase();

                    if (query === "") {
                        this.getOptions = this.originalOptions;

                        return;
                    }

                    this.getOptions = this.originalOptions.filter(item => item.label.toLowerCase().includes(query));
                },

                selectOption(option) {
                    this.selectedOption = option;

                    this.$refs.dropdown.toggle();
                },
            },
        });
    </script>
@endPushOnce