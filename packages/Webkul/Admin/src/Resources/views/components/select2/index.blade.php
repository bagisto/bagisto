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
    <x-admin::form.control-group.control type="select"/>
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
                v-model="selectedOption.id"
                :rules="rules"
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
                                        v-model="searchQuery"
                                    >
                                </li>

                                <ul class="p-2 max-h-40 overflow-y-auto">
                                    <li v-if="! items.length">
                                        <p
                                            class="block p-2 text-gray-600 dark:text-gray-300"
                                            v-text="'@lang('admin::app.components.datagrid.filters.dropdown.searchable.no-results')'"
                                        >
                                        </p>
                                    </li>

                                    <li
                                        v-for="item in items"
                                        v-else
                                    >
                                        <p
                                            class="text-sm text-gray-600 dark:text-gray-300 p-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950"
                                            :class="{ 'bg-gray-100': item.id == selectedOption.id }"
                                            v-text="item.label"
                                            @click="select(item)"
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

            props: [
                'id',
                'name',
                'rules',
                'value',
                'label',
                'options',
                'selected',
                'errors',
            ],

            data() {
                return {
                    selectedOption: {},

                    searchQuery: '',
                };
            },

            mounted() {
                let selected = this.parsedOptions.find(item => this.value == item.id);

                this.selectedOption = selected ? selected : this.parsedOptions[0];
            },

            computed: {
                parsedOptions() {
                    return JSON.parse(this.options);
                },

                items() {
                    const query = this.searchQuery.toLowerCase();

                    return this.parsedOptions.filter(item => item.label.toLowerCase().includes(query));
                },
            },

            methods: {
                select(item) {
                    this.selectedOption = item;

                    this.searchQuery = '';

                    this.$refs.dropdown.toggle();
                },
            },
        });
    </script>
@endPushOnce