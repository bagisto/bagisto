@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-tree-checkbox-template"
    >
        <label
            :for="id"
            class="group inline-flex w-max cursor-pointer select-none items-center gap-2.5 p-1.5"
        >
            <input
                type="checkbox"
                :name="[name + '[]']"
                :value="value"
                :id="id"
                class="peer hidden"
                :checked="isActive"
                @change="inputChanged()"
            />

            <span class="icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:text-blue-600">
            </span>

            <div class="cursor-pointer text-sm text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white">
                @{{ label }}
            </div>
        </label>
    </script>

    <script type="module">
        app.component('v-tree-checkbox', {
            template: '#v-tree-checkbox-template',

            name: 'v-tree-checkbox',

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
