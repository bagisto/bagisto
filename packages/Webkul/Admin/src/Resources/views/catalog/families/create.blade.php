<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.families.create.title')
    </x-slot>

    <!-- Input Form -->
    <x-admin::form :action="route('admin.catalog.families.store')">

        {!! view_render_event('bagisto.admin.catalog.families.create.create_form_controls.before') !!}

        <!-- Page Header -->
        <div class="flex justify-between items-center">
            <p class="text-xl text-gray-800 dark:text-white font-bold">
                @lang('admin::app.catalog.families.create.title')
            </p>

            <div class="flex gap-x-2.5 items-center">
                <a
                    href="{{ route('admin.catalog.families.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                >
                    @lang('admin::app.catalog.families.create.back-btn')
                </a>

                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.catalog.families.create.save-btn')
                </button>
            </div>
        </div>

        <!-- Container -->
        <div class="flex gap-2.5 mt-3.5">
            <!-- Left Container -->
            <div class="flex flex-col gap-2 flex-1 bg-white dark:bg-gray-900 rounded box-shadow">
                <v-family-attributes>
                    <x-admin::shimmer.families.attributes-panel />
                </v-family-attributes>
            </div>

            <!-- Right Container -->
            <div class="flex flex-col gap-2 w-[360px] max-w-full select-none">
                <!-- General Pannel -->
                <x-admin::accordion>
                    <!-- Panel Header -->
                    <x-slot:header>
                        <p class="p-2.5 text-gray-800 dark:text-white text-base font-semibold">
                            @lang('admin::app.catalog.families.create.general')
                        </p>
                    </x-slot>
                
                    <!-- Panel Content -->
                    <x-slot:content>
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required !text-gray-800 dark:!text-white">
                                @lang('admin::app.catalog.families.create.code')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="code"
                                rules="required"
                                value="{{ old('code') }}"
                                :label="trans('admin::app.catalog.families.create.code')"
                                :placeholder="trans('admin::app.catalog.families.create.enter-code')"
                            />

                            <x-admin::form.control-group.error control-name="code" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label class="required !text-gray-800 dark:!text-white">
                                @lang('admin::app.catalog.families.create.name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                rules="required"
                                value="{{ old('name') }}"
                                :label="trans('admin::app.catalog.families.create.name')"
                                :placeholder="trans('admin::app.catalog.families.create.enter-name')"
                            />

                            <x-admin::form.control-group.error control-name="name" />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.families.create.create_form_controls.after') !!}

    </x-admin::form>

    @pushOnce('scripts')
        <script 
            type="text/x-template" 
            id="v-family-attributes-template"
        >
            <div>
                <!-- Panel Header -->
                <div class="flex flex-wrap gap-2.5 justify-between mb-2.5 p-4">
                    <!-- Panel Header -->
                    <div class="flex flex-col gap-2">
                        <p class="text-base text-gray-800 dark:text-white font-semibold">
                            @lang('admin::app.catalog.families.create.groups')
                        </p>

                        <p class="text-xs text-gray-500 dark:text-gray-300 font-medium">
                            @lang('admin::app.catalog.families.create.groups-info')
                        </p>
                    </div>
                    
                    <!-- Panel Content -->
                    <div class="flex gap-x-1 items-center">
                        <!-- Delete Group Button -->
                        <div
                            class="px-3 py-1.5 border-2 border-transparent rounded-md text-red-600 font-semibold whitespace-nowrap transition-all hover:bg-gray-100 dark:hover:bg-gray-950 cursor-pointer"
                            @click="deleteGroup"
                        >
                            @lang('admin::app.catalog.families.create.delete-group-btn')
                        </div>

                        <!-- Add Group Button -->
                        <div
                            class="secondary-button"
                            @click="$refs.addGroupModal.open()"
                        >
                            @lang('admin::app.catalog.families.create.add-group-btn')
                        </div>
                    </div>
                </div>

                <!-- Panel Content -->
                <div class="flex [&>*]:flex-1 gap-5 justify-between px-4">
                    <!-- Attributes Groups Container -->
                    <div v-for="(groups, column) in columnGroups">
                        <!-- Attributes Groups Header -->
                        <div class="flex flex-col mb-4">
                            <p class="text-gray-600 dark:text-gray-300 font-semibold leading-6">
                                @{{
                                    column == 1
                                    ? "@lang('admin::app.catalog.families.create.main-column')"
                                    : "@lang('admin::app.catalog.families.create.right-column')"
                                }}
                            </p>
                            
                            <p class="text-xs text-gray-800 dark:text-white font-medium">
                                @lang('admin::app.catalog.families.create.edit-group-info')
                            </p>
                        </div>

                        <!-- Draggable Attribute Groups -->
                        <draggable
                            class="h-[calc(100vh-285px)] pb-4 overflow-auto ltr:border-r rtl:border-l border-gray-200"
                            ghost-class="draggable-ghost"
                            handle=".icon-drag"
                            v-bind="{animation: 200}"
                            :list="groups"
                            item-key="id"
                            group="groups"
                        >
                            <template #item="{ element, index }">
                                <div class="">
                                    <!-- Group Container -->
                                    <div class="flex items-center group">
                                        <!-- Toggle -->
                                        <i
                                            class="icon-sort-down text-xl rounded-md cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950 group-hover:text-gray-800 dark:group-hover:text-white"
                                            @click="element.hide = ! element.hide"
                                        >
                                        </i>

                                        <!-- Group Name -->
                                        <div
                                            class="group_node flex gap-1.5 max-w-max py-1.5 ltr:pr-1.5 rtl:pl-1.5 rounded text-gray-600 dark:text-gray-300 group cursor-pointer transition-all group-hover:text-gray-800 dark:group-hover:text-white"
                                            :class="{'bg-blue-600 text-white group-hover:[&>*]:text-white': selectedGroup.id == element.id}"
                                            @click.stop="groupSelected(element)"
                                        >
                                            <i class="icon-drag text-xl text-inherit transition-all group-hover:text-gray-800 dark:group-hover:text-white cursor-grab"></i>

                                            <i
                                                class="text-xl text-inherit transition-all group-hover:text-gray-800 dark:group-hover:text-white"
                                                :class="[element.is_user_defined ? 'icon-folder' : 'icon-folder-block']"
                                            >
                                            </i>

                                            <span
                                                class="text-sm text-inherit font-regular transition-all group-hover:text-gray-800 dark:group-hover:text-white"
                                                v-show="editableGroup.id != element.id"
                                                v-text="element.name"
                                            >
                                            </span>

                                            <input
                                                type="hidden"
                                                :name="'attribute_groups[' + element.id + '][code]'"
                                                :value="element.code"
                                            />

                                            <input
                                                type="text"
                                                :name="'attribute_groups[' + element.id + '][name]'"
                                                class="group_node text-sm !text-gray-600 dark:!text-gray-300"
                                                v-model="element.name"
                                                v-show="editableGroup.id == element.id"
                                            />

                                            <input
                                                type="hidden"
                                                :name="'attribute_groups[' + element.id + '][position]'"
                                                :value="index + 1"
                                            />

                                            <input
                                                type="hidden"
                                                :name="'attribute_groups[' + element.id + '][column]'"
                                                :value="column"
                                            />
                                        </div>
                                    </div>

                                    <!-- Group Attributes -->
                                    <draggable
                                        class="ltr:ml-11 rtl:mr-11"
                                        ghost-class="draggable-ghost"
                                        handle=".icon-drag"
                                        v-bind="{animation: 200}"
                                        :list="getGroupAttributes(element)"
                                        item-key="id"
                                        group="attributes"
                                        :move="onMove"
                                        @end="onEnd"
                                        v-show="! element.hide"
                                    >
                                        <template #item="{ element, index }">
                                            <div class="flex gap-1.5 max-w-max py-1.5 ltr:pr-1.5 rtl:pl-1.5 rounded text-gray-600 dark:text-gray-300 group">
                                                <i class="icon-drag text-xl transition-all group-hover:text-gray-800 dark:group-hover:text-white cursor-grab"></i>

                                                <i
                                                    class="text-xl transition-all group-hover:text-gray-800 dark:group-hover:text-white"
                                                    :class="[element.is_user_defined ? 'icon-attribute' : 'icon-attribute-block']"
                                                >
                                                </i>
                                                

                                                <span 
                                                    class="text-sm font-regular transition-all group-hover:text-gray-800 dark:group-hover:text-white max-xl:text-xs"
                                                    v-text="element.admin_name"
                                                >
                                                </span>

                                                <input
                                                    type="hidden"
                                                    :name="'attribute_groups[' + element.group_id + '][custom_attributes][' + index + '][id]'"
                                                    v-model="element.id"
                                                />

                                                <input
                                                    type="hidden"
                                                    :name="'attribute_groups[' + element.group_id + '][custom_attributes][' + index + '][position]'"
                                                    :value="index + 1"
                                                />
                                            </div>
                                        </template>
                                    </draggable>
                                </div>
                            </template>
                        </draggable>
                    </div>

                    <!-- Unassigned Attributes Container -->
                    <div class="">
                        <!-- Unassigned Attributes Header -->
                        <div class="flex flex-col mb-4">
                            <p class="text-gray-600 dark:text-gray-300 font-semibold leading-6">
                                @lang('admin::app.catalog.families.create.unassigned-attributes')
                            </p>

                            <p class="text-xs text-gray-800 dark:text-white font-medium ">
                                @lang('admin::app.catalog.families.create.unassigned-attributes-info')
                            </p>
                        </div>

                        <!-- Draggable Unassigned Attributes -->
                        <draggable
                            id="unassigned-attributes"
                            class="h-[calc(100vh-285px)] pb-4 overflow-auto"
                            ghost-class="draggable-ghost"
                            handle=".icon-drag"
                            v-bind="{animation: 200}"
                            :list="unassignedAttributes"
                            item-key="id"
                            group="attributes"
                        >
                            <template #item="{ element }">
                                <div class="flex gap-1.5 max-w-max py-1.5 ltr:pr-1.5 rtl:pl-1.5 rounded text-gray-600 dark:text-gray-300 group">
                                    <i class="icon-drag text-xl transition-all group-hover:text-gray-800 dark:group-hover:text-white cursor-grab"></i>

                                    <i class="text-xl transition-all group-hover:text-gray-800 dark:group-hover:text-white"></i>

                                    <span 
                                        class="text-sm font-regular transition-all group-hover:text-gray-800 dark:group-hover:text-white max-xl:text-xs"
                                        v-text="element.admin_name"    
                                    >
                                    </span>
                                </div>
                            </template>
                        </draggable>
                    </div>
                </div>

                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, addGroup)">
                        <!-- Model Form -->
                        <x-admin::modal ref="addGroupModal">
                            <!-- Model Header -->
                            <x-slot:header>
                                <p class="text-lg text-gray-800 dark:text-white font-bold">
                                    @lang('admin::app.catalog.families.create.add-group-title')
                                </p>
                            </x-slot>

                            <!--Model Content -->
                            <x-slot:content>
                                <!-- Group Code -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.families.create.code')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="code"
                                        rules="required"
                                        :label="trans('admin::app.catalog.families.create.code')"
                                        :placeholder="trans('admin::app.catalog.families.create.code')"
                                    />

                                    <x-admin::form.control-group.error control-name="code" />
                                </x-admin::form.control-group>

                                <!-- Group Name -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.families.create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        rules="required"
                                        :label="trans('admin::app.catalog.families.create.name')"
                                        :placeholder="trans('admin::app.catalog.families.create.name')"
                                    />

                                    <x-admin::form.control-group.error control-name="name" />
                                </x-admin::form.control-group>

                                <!-- Select Group Type -->
                                <x-admin::form.control-group class="mb-4">
                                    <x-admin::form.control-group.label class="required !text-gray-800 font-medium">
                                        @lang('admin::app.catalog.families.create.column')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="column"
                                        rules="required"
                                        :label="trans('admin::app.catalog.families.create.column')"
                                    >
                                        <!-- Default Option -->
                                        <option value="">
                                            @lang('admin::app.catalog.families.create.select-group')
                                        </option>

                                        <option value="1">
                                            @lang('admin::app.catalog.families.create.main-column')
                                        </option>

                                        <option value="2">
                                            @lang('admin::app.catalog.families.create.right-column')
                                        </option>
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error control-name="column" /> 
                                </x-admin::form.control-group>
                            </x-slot>

                            <!-- Model Footer -->
                            <x-slot:footer>
                                <div class="flex gap-x-2.5 items-center">
                                    <!-- Add Group Button -->
                                    <button 
                                        type="submit"
                                        class="primary-button"
                                    >
                                        @lang('admin::app.catalog.families.create.add-group-btn')
                                    </button>
                                </div>
                            </x-slot>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-family-attributes', {
                template: '#v-family-attributes-template',

                data: function () {
                    return {
                        selectedGroup: {
                            id: null,
                            code: null,
                            name: null,
                        },

                        editableGroup: {
                            id: null,
                            code: null,
                            name: null,
                        },

                        columnGroups: @json($attributeFamily->attribute_groups->groupBy('column')),

                        customAttributes: @json($customAttributes),

                        dropReverted: false,
                    }
                },

                created() {
                    window.addEventListener('click', this.handleFocusOut);
                },

                computed: {
                    unassignedAttributes() {
                        return this.customAttributes.filter(attribute => {
                            return ! this.columnGroups[1].find(group => group.custom_attributes.find(customAttribute => customAttribute.id == attribute.id))
                                && ! this.columnGroups[2]?.find(group => group.custom_attributes.find(customAttribute => customAttribute.id == attribute.id));
                        });
                    },
                },

                methods: {
                    onMove: function(e) {
                        if (
                            e.to.id === 'unassigned-attributes'
                            && ! e.draggedContext.element.is_user_defined
                        ) {
                            this.dropReverted = true;

                            return false;
                        } else {
                            this.dropReverted = false;
                        }
                    },

                    onEnd: function(e) {
                        if (this.dropReverted) {
                            this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('admin::app.catalog.families.create.removal-not-possible')" });
                        }
                    },

                    getGroupAttributes(group) {
                        group.custom_attributes.forEach((attribute, index) => {
                            attribute.group_id = group.id;
                        });

                        return group.custom_attributes;
                    },

                    groupSelected(group) {
                        if (this.selectedGroup.id) {
                            this.editableGroup = this.selectedGroup.id == group.id
                                ? group
                                : {
                                    id: null,
                                    code: null,
                                    name: null,
                                };
                        }

                        this.selectedGroup = group;
                    },

                    addGroup(params, { resetForm, setErrors }) {
                        let isGroupCodeAlreadyExists = this.isGroupCodeAlreadyExists(params.code);

                        let isGroupNameAlreadyExists = this.isGroupNameAlreadyExists(params.name);

                        if (isGroupCodeAlreadyExists || isGroupCodeAlreadyExists) {
                            if (isGroupCodeAlreadyExists) {
                                setErrors({'code': ["@lang('admin::app.catalog.families.create.group-code-already-exists')"]});
                            }

                            if (isGroupNameAlreadyExists) {
                                setErrors({'name': ["@lang('admin::app.catalog.families.create.group-name-already-exists')"]});
                            }

                            return;
                        }

                        if (! this.columnGroups.hasOwnProperty(params.column)) {
                            this.columnGroups[params.column] = [];
                        }

                        this.columnGroups[params.column].push({
                            'id': 'group_' + params.column + '_' + this.columnGroups[params.column].length,
                            'name': params.name,
                            'is_user_defined': 1,
                            'custom_attributes': [],
                        });

                        resetForm();

                        this.$refs.addGroupModal.close();
                    },

                    isGroupCodeAlreadyExists(code) {
                        return this.columnGroups[1].find(group => group.code == code) || this.columnGroups[2].find(group => group.code == code);
                    },
                    
                    isGroupNameAlreadyExists(name) {
                        return this.columnGroups[1].find(group => group.name == name) || this.columnGroups[2]?.find(group => group.name == name);
                    },
                    
                    isGroupContainsSystemAttributes(group) {
                        return group.custom_attributes.find(attribute => ! attribute.is_user_defined);
                    },

                    deleteGroup() {
                        if (! this.selectedGroup.id) {
                            this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('admin::app.catalog.families.create.select-group')" });

                            return;
                        }

                        if (this.isGroupContainsSystemAttributes(this.selectedGroup)) {
                            this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('admin::app.catalog.families.create.group-contains-system-attributes')" });

                            return;
                        }

                        for (const [key, groups] of Object.entries(this.columnGroups)) {
                            let index = groups.indexOf(this.selectedGroup);

                            if (index > -1) {
                                groups.splice(index, 1);
                            }
                        }
                    },

                    handleFocusOut(e) {
                        if (! e.target.classList.contains('group_node')) {
                            this.editableGroup = {
                                id: null,
                                code: null,
                                name: null,
                            };
                        }
                    },
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>
