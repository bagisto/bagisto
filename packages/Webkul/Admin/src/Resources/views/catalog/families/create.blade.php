@php
    $locales = core()->getAllLocales()->map(fn ($locale) => ['code' => $locale->code, 'name' => $locale->name])->values();

    $columnGroups = $attributeFamily->attribute_groups
        ->map(fn ($group) => [
            'id' => $group->id,
            'code' => $group->code,
            'admin_name' => $group->admin_name,
            'column' => $group->column,
            'is_user_defined' => $group->is_user_defined,
            'names' => (object) $group->translations->pluck('name', 'locale')->all(),
            'custom_attributes' => $group->custom_attributes,
        ])
        ->groupBy('column');
@endphp

<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.families.create.title')
    </x-slot>

    <!-- Input Form -->
    <x-admin::form :action="route('admin.catalog.families.store')">

        {!! view_render_event('bagisto.admin.catalog.families.create.create_form_controls.before') !!}

        <!-- Page Header -->
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.catalog.families.create.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <a
                    href="{{ route('admin.catalog.families.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
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
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Container -->
            <div class="box-shadow flex flex-1 flex-col gap-2 rounded-sm bg-white dark:bg-gray-900 max-xl:flex-auto">
                <v-family-attributes>
                    <x-admin::shimmer.catalog.families.attributes-panel />
                </v-family-attributes>
            </div>

            <!-- Right Container -->
            <div class="flex w-90 max-w-full select-none flex-col gap-2">
                <!-- General Panel -->
                <x-admin::accordion>
                    <!-- Panel Header -->
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.catalog.families.create.general')
                        </p>
                    </x-slot>

                    <!-- Panel Content -->
                    <x-slot:content>
                        <!-- Code -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required text-gray-800! dark:text-white!">
                                @lang('admin::app.catalog.families.create.code')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="code"
                                rules="required"
                                :value="old('code')"
                                :label="trans('admin::app.catalog.families.create.code')"
                                :placeholder="trans('admin::app.catalog.families.create.enter-code')"
                            />

                            <x-admin::form.control-group.error control-name="code" />
                        </x-admin::form.control-group>

                        <!-- Name -->
                        <x-admin::form.control-group class="mb-0!">
                            <x-admin::form.control-group.label class="required text-gray-800! dark:text-white!">
                                @lang('admin::app.catalog.families.create.name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                rules="required"
                                :value="old('name')"
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
                <div class="mb-2.5 flex flex-wrap justify-between gap-2.5 p-4">
                    <!-- Panel Header -->
                    <div class="flex flex-col gap-2">
                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.catalog.families.create.groups')
                        </p>

                        <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                            @lang('admin::app.catalog.families.create.groups-info')
                        </p>
                    </div>

                    <!-- Panel Content -->
                    <div class="flex items-center gap-x-1">
                        <!-- Delete Group Button -->
                        <div
                            class="cursor-pointer whitespace-nowrap rounded-md border-2 border-transparent px-3 py-1.5 font-semibold text-red-600 transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                            @click="deleteGroup"
                        >
                            @lang('admin::app.catalog.families.create.delete-group-btn')
                        </div>

                        <!-- Edit Group Button -->
                        <div
                            class="transparent-button"
                            @click="editGroup"
                        >
                            @lang('admin::app.catalog.families.create.edit-group-btn')
                        </div>

                        <!-- Add Group Button -->
                        <div
                            class="secondary-button"
                            @click="openGroupModal()"
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
                        <div class="mb-4 flex flex-col">
                            <p class="font-semibold leading-6 text-gray-600 dark:text-gray-300">
                                @{{
                                    column == 1
                                    ? "@lang('admin::app.catalog.families.create.main-column')"
                                    : "@lang('admin::app.catalog.families.create.right-column')"
                                }}
                            </p>

                            <p class="text-xs font-medium text-gray-800 dark:text-white">
                                @lang('admin::app.catalog.families.create.edit-group-info')
                            </p>
                        </div>

                        <!-- Draggable Attribute Groups -->
                        <draggable
                            class="h-[calc(100vh-285px)] overflow-auto border-gray-200 pb-4 ltr:border-r rtl:border-l"
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
                                    <div class="group flex items-center">
                                        <!-- Toggle -->
                                        <i
                                            class="icon-sort-down cursor-pointer rounded-md text-xl transition-all hover:bg-gray-100 group-hover:text-gray-800 dark:hover:bg-gray-950 dark:group-hover:text-white"
                                            @click="element.hide = ! element.hide"
                                        >
                                        </i>

                                        <!-- Group Name -->
                                        <div
                                            class="group_node group flex max-w-max cursor-pointer gap-1.5 rounded-sm py-1.5 text-gray-600 transition-all group-hover:text-gray-800 dark:text-gray-300 dark:group-hover:text-white ltr:pr-1.5 rtl:pl-1.5"
                                            :class="{'bg-blue-600 text-white dark:text-white group-hover:[&>*]:text-white': selectedGroup.id == element.id}"
                                            @click.stop="groupSelected(element)"
                                        >
                                            <i class="icon-drag cursor-grab text-xl text-inherit transition-all group-hover:text-gray-800 dark:group-hover:text-white"></i>

                                            <i
                                                class="text-xl text-inherit transition-all group-hover:text-gray-800 dark:group-hover:text-white"
                                                :class="[element.is_user_defined ? 'icon-folder' : 'icon-folder-block']"
                                            >
                                            </i>

                                            <span
                                                class="font-regular text-sm text-inherit transition-all group-hover:text-gray-800 dark:group-hover:text-white"
                                                v-show="editableGroup.id != element.id"
                                            >
                                                @{{ element.admin_name }}
                                            </span>

                                            <input
                                                type="hidden"
                                                :name="'attribute_groups[' + element.id + '][code]'"
                                                :value="element.code"
                                            />

                                            <input
                                                type="text"
                                                class="group_node rounded-sm border border-white bg-white px-1.5 text-sm text-gray-800! outline-hidden dark:border-gray-900 dark:bg-gray-900 dark:text-white!"
                                                :name="'attribute_groups[' + element.id + '][admin_name]'"
                                                v-model="element.admin_name"
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

                                            <!-- Name In Each Locale -->
                                            <input
                                                type="hidden"
                                                v-for="locale in locales"
                                                :name="'attribute_groups[' + element.id + '][' + locale.code + '][name]'"
                                                :value="element.names[locale.code]"
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
                                            <div class="group flex max-w-max gap-1.5 rounded-sm py-1.5 text-gray-600 dark:text-gray-300 ltr:pr-1.5 rtl:pl-1.5">
                                                <i class="icon-drag cursor-grab text-xl transition-all group-hover:text-gray-800 dark:group-hover:text-white"></i>

                                                <i
                                                    class="text-xl transition-all group-hover:text-gray-800 dark:group-hover:text-white"
                                                    :class="[element.is_user_defined ? 'icon-attribute' : 'icon-attribute-block']"
                                                >
                                                </i>


                                                <span class="font-regular text-sm transition-all group-hover:text-gray-800 dark:group-hover:text-white max-xl:text-xs">
                                                    @{{ element.admin_name }}
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
                        <div class="mb-4 flex flex-col">
                            <p class="font-semibold leading-6 text-gray-600 dark:text-gray-300">
                                @lang('admin::app.catalog.families.create.unassigned-attributes')
                            </p>

                            <p class="text-xs font-medium text-gray-800 dark:text-white">
                                @lang('admin::app.catalog.families.create.unassigned-attributes-info')
                            </p>
                        </div>

                        <!-- Draggable Unassigned Attributes -->
                        <draggable
                            id="unassigned-attributes"
                            class="h-[calc(100vh-285px)] overflow-auto pb-4"
                            ghost-class="draggable-ghost"
                            handle=".icon-drag"
                            v-bind="{animation: 200}"
                            :list="unassignedAttributes"
                            item-key="id"
                            group="attributes"
                        >
                            <template #item="{ element }">
                                <div class="group flex max-w-max gap-1.5 rounded-sm py-1.5 text-gray-600 dark:text-gray-300 ltr:pr-1.5 rtl:pl-1.5">
                                    <i class="icon-drag cursor-grab text-xl transition-all group-hover:text-gray-800 dark:group-hover:text-white"></i>

                                    <i class="text-xl transition-all group-hover:text-gray-800 dark:group-hover:text-white"></i>

                                    <span class="font-regular text-sm transition-all group-hover:text-gray-800 dark:group-hover:text-white max-xl:text-xs">
                                        @{{ element.admin_name }}
                                    </span>
                                </div>
                            </template>
                        </draggable>
                    </div>
                </div>

                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                    ref="groupForm"
                >
                    <form @submit="handleSubmit($event, saveGroup)">
                        <x-admin::modal
                            ref="groupModal"
                            panel-class="max-w-192"
                        >
                            <!-- Modal Header -->
                            <x-slot:header>
                                <p
                                    class="text-lg font-bold text-gray-800 dark:text-white"
                                    v-if="editingGroup"
                                >
                                    @lang('admin::app.catalog.families.create.edit-group-title')
                                </p>

                                <p
                                    class="text-lg font-bold text-gray-800 dark:text-white"
                                    v-else
                                >
                                    @lang('admin::app.catalog.families.create.add-group-title')
                                </p>
                            </x-slot>

                            <!-- Modal Content -->
                            <x-slot:content class="max-h-[70vh] overflow-y-auto">
                                <!-- Group Code -->
                                <x-admin::form.control-group v-if="! editingGroup">
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

                                <!-- Group Admin Name -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.families.create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="admin_name"
                                        rules="required"
                                        :label="trans('admin::app.catalog.families.create.name')"
                                        :placeholder="trans('admin::app.catalog.families.create.name')"
                                    />

                                    <x-admin::form.control-group.error control-name="admin_name" />
                                </x-admin::form.control-group>

                                <!-- Group Column -->
                                <x-admin::form.control-group v-if="! editingGroup">
                                    <x-admin::form.control-group.label class="required">
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

                                <!-- Name In Each Locale -->
                                <div class="border-t pt-4 dark:border-gray-800">
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                        @lang('admin::app.catalog.families.create.group-labels')
                                    </p>

                                    <p class="mb-4 text-xs font-medium text-gray-500 dark:text-gray-300">
                                        @lang('admin::app.catalog.families.create.group-labels-info')
                                    </p>

                                    <div class="grid grid-cols-3 gap-x-4 max-sm:grid-cols-1">
                                        @foreach (core()->getAllLocales() as $locale)
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label>
                                                    {{ $locale->name . ' (' . strtoupper($locale->code) . ')' }}
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    :name="'name_' . $locale->code"
                                                    :label="$locale->name"
                                                    :placeholder="$locale->name"
                                                />

                                                <x-admin::form.control-group.error :control-name="'name_' . $locale->code" />
                                            </x-admin::form.control-group>
                                        @endforeach
                                    </div>
                                </div>
                            </x-slot>

                            <!-- Modal Footer -->
                            <x-slot:footer>
                                <!-- Save Button -->
                                <x-admin::button
                                    class="primary-button"
                                    :title="trans('admin::app.catalog.families.create.save-group-btn')"
                                    v-if="editingGroup"
                                />

                                <x-admin::button
                                    class="primary-button"
                                    :title="trans('admin::app.catalog.families.create.add-group-btn')"
                                    v-else
                                />
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

                        editingGroup: null,

                        locales: @json($locales),

                        columnGroups: @json($columnGroups),

                        customAttributes: @json($customAttributes),

                        dropReverted: false,
                    }
                },

                created() {
                    window.addEventListener('click', this.handleFocusOut);
                },

                computed: {
                    /**
                     * Attributes that no group of the family holds yet.
                     */
                    unassignedAttributes() {
                        return this.customAttributes.filter(attribute => {
                            return ! this.columnGroups[1].find(group => group.custom_attributes.find(customAttribute => customAttribute.id == attribute.id))
                                && ! this.columnGroups[2]?.find(group => group.custom_attributes.find(customAttribute => customAttribute.id == attribute.id));
                        });
                    },
                },

                methods: {
                    /**
                     * Refuse a system attribute dragged back to the unassigned list.
                     */
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

                    /**
                     * Warn once a drag has been refused.
                     */
                    onEnd: function(e) {
                        if (this.dropReverted) {
                            this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('admin::app.catalog.families.create.removal-not-possible')" });
                        }
                    },

                    /**
                     * A group's attributes, each tagged with the id of the group.
                     */
                    getGroupAttributes(group) {
                        group.custom_attributes.forEach((attribute, index) => {
                            attribute.group_id = group.id;
                        });

                        return group.custom_attributes;
                    },

                    /**
                     * Select a group, and make it renameable in place on a second click, ready to type into.
                     */
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

                        if (this.editableGroup.id == group.id) {
                            this.$nextTick(() => this.$el.querySelector(`input[name="attribute_groups[${group.id}][admin_name]"]`)?.focus());
                        }
                    },

                    /**
                     * Open the group popup, empty for a new group or filled from the given one.
                     */
                    openGroupModal(group = null) {
                        this.editingGroup = group;

                        let values = { code: group?.code ?? '', admin_name: group?.admin_name ?? '', column: '' };

                        this.locales.forEach(locale => values['name_' + locale.code] = group?.names[locale.code] ?? '');

                        this.$refs.groupForm.resetForm({ values });

                        this.$refs.groupModal.open();
                    },

                    /**
                     * Open the group popup for the selected group.
                     */
                    editGroup() {
                        if (! this.selectedGroup.id) {
                            this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('admin::app.catalog.families.create.select-group')" });

                            return;
                        }

                        this.openGroupModal(this.selectedGroup);
                    },

                    /**
                     * Apply the popup to the group being edited, or add it as a new group.
                     */
                    saveGroup(params, { resetForm, setErrors }) {
                        let names = {};

                        this.locales.forEach(locale => names[locale.code] = params['name_' + locale.code] ?? '');

                        if (this.editingGroup) {
                            let sameName = this.isGroupNameAlreadyExists(params.admin_name);

                            if (
                                sameName
                                && sameName !== this.editingGroup
                            ) {
                                setErrors({'admin_name': ["@lang('admin::app.catalog.families.create.group-name-already-exists')"]});

                                return;
                            }

                            this.editingGroup.admin_name = params.admin_name;

                            this.editingGroup.names = names;
                        } else {
                            let isGroupCodeAlreadyExists = this.isGroupCodeAlreadyExists(params.code);

                            let isGroupNameAlreadyExists = this.isGroupNameAlreadyExists(params.admin_name);

                            if (
                                isGroupCodeAlreadyExists
                                || isGroupNameAlreadyExists
                            ) {
                                if (isGroupCodeAlreadyExists) {
                                    setErrors({'code': ["@lang('admin::app.catalog.families.create.group-code-already-exists')"]});
                                }

                                if (isGroupNameAlreadyExists) {
                                    setErrors({'admin_name': ["@lang('admin::app.catalog.families.create.group-name-already-exists')"]});
                                }

                                return;
                            }

                            this.columnGroups[params.column].push({
                                'id': 'group_' + params.column + '_' + this.columnGroups[params.column].length,
                                'code': params.code,
                                'admin_name': params.admin_name,
                                'names': names,
                                'is_user_defined': 1,
                                'custom_attributes': [],
                            });
                        }

                        resetForm();

                        this.$refs.groupModal.close();
                    },

                    /**
                     * The group already using the given code, if any.
                     */
                    isGroupCodeAlreadyExists(code) {
                        return this.columnGroups[1].find(group => group.code == code)
                            || this.columnGroups[2].find(group => group.code == code);
                    },

                    /**
                     * The group already using the given admin name, if any.
                     */
                    isGroupNameAlreadyExists(name) {
                        return this.columnGroups[1].find(group => group.admin_name == name)
                            || this.columnGroups[2]?.find(group => group.admin_name == name);
                    },

                    /**
                     * The first system attribute the group holds, if any.
                     */
                    isGroupContainsSystemAttributes(group) {
                        return group.custom_attributes.find(attribute => ! attribute.is_user_defined);
                    },

                    /**
                     * Remove the selected group, unless it holds system attributes.
                     */
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

                    /**
                     * Leave in-place renaming on a click outside the group name.
                     */
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
