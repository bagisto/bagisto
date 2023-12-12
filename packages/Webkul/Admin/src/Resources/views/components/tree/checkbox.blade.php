@pushOnce('scripts')
    <script type="text/x-template" id="v-tree-checkbox-template">
        <label
            :for="id"
            class="inline-flex gap-[10px] w-max p-[6px] items-center cursor-pointer select-none group"
        >
            <input
                type="checkbox"
                :name="[name + '[]']"
                :value="value"
                :id="id"
                class="hidden peer"
                :checked="isActive"
                @change="inputChanged()"
            >

            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-blue-600">
            </span>

            <div
                class="text-[14px] text-gray-600 dark:text-gray-300 cursor-pointer hover:text-gray-800 dark:hover:text-white"
                v-text="label"
            >
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
