@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-tree-radio-template"
    >
        <label
            :for="id"
            class="inline-flex items-center w-max p-1.5 text-gray-600 dark:text-gray-300 cursor-pointer select-none"
        >
            <input
                type="radio"
                :name="name"
                :value="value"
                :id="id"
                class="hidden peer"
                :checked="isActive"
                @change="inputChanged()"
            >

            <span class="icon-radio-normal mr-1 text-2xl rounded-md cursor-pointer peer-checked:icon-radio-selected peer-checked:text-blue-600"></span>

            <div
                class="text-sm text-gray-600 dark:text-gray-300 cursor-pointer hover:text-gray-800 dark:hover:text-white"
                v-text="label"
            >
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
