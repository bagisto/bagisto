@pushOnce('scripts')
    {{-- v-tree-radio-component template --}}
    <script
        type="text/x-template"
        id="v-tree-radio-template"
    >
        <label
            :for="id"
            class="inline-flex items-center w-max p-[6px] text-gray-600 dark:text-gray-300 cursor-pointer select-none"
        >
            <input
                type="radio"
                :name="nameField"
                :value="modelValue"
                :id="id"
                class="hidden peer"
                :checked="isActive"
            >

            <span class="icon-radio-normal mr-[4px] text-[24px] rounded-[6px] cursor-pointer peer-checked:icon-radio-selected peer-checked:text-blue-600"></span>

            <div 
                class="text-[14px] text-gray-600 dark:text-gray-300 cursor-pointer hover:text-gray-800 dark:hover:text-white"
                v-text="label"
            >
            </div>
        </label>
    </script>

    {{-- v-tree-radio component --}}
    <script type="module">
        app.component('v-tree-radio', {
            template: '#v-tree-radio-template',

            name: 'v-tree-radio',

            props: ['id', 'label', 'nameField', 'modelValue', 'value'],

            computed: {
                isActive() {
                    if(this.value.length) {
                        return this.value[0] == this.modelValue ? true : false;
                    }

                    return false
                }
            }
        });
    </script>
@endPushOnce