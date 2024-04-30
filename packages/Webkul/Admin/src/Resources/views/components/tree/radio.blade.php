@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-tree-radio-template"
    >
        <label
            :for="id"
            class="inline-flex w-max cursor-pointer select-none items-center p-1.5 text-gray-600 dark:text-gray-300"
        >
            <input
                type="radio"
                :name="name"
                :value="value"
                :id="id"
                class="peer hidden"
                :checked="isActive"
                @change="inputChanged()"
            >

            <span class="icon-radio-normal peer-checked:icon-radio-selected mr-1 cursor-pointer rounded-md text-2xl peer-checked:text-blue-600"></span>

            <div class="cursor-pointer text-sm text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white">
                @{{ label }}
            </div>
        </label>
    </script>

    <script type="module">
        app.component('v-tree-radio', {
            template: '#v-tree-radio-template',

            name: 'v-tree-radio',

            props: ['id', 'label', 'name', 'value'],

            computed: {
                isActive() {
                    return this.$parent.has(this.value);
                },
            },

            methods: {
                inputChanged() {
                    this.$emit('change-input', {
                        id: this.id,
                        label: this.label,
                        name: this.name,
                        value: this.value,
                    });
                },
            },
        });
    </script>
@endPushOnce
