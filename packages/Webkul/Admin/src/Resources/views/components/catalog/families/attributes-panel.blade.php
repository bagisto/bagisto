@props([
    'family',
    'customAttributes',
    'translation',
])

@php
    $locales = core()->getAllLocales()->map(fn ($locale) => ['code' => $locale->code, 'name' => $locale->name])->values();

    $columnGroups = $family->attribute_groups
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

<v-family-attributes>
    <x-admin::shimmer.catalog.families.attributes-panel />
</v-family-attributes>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-family-attributes-template"
    >
        <div>
            <!-- Panel Header -->
            <div class="flex flex-wrap items-center justify-between gap-2.5 border-b p-4 dark:border-gray-800">
                <div class="flex flex-col gap-1">
                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                        @lang($translation.'.groups')
                    </p>

                    <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                        @lang($translation.'.groups-info')
                    </p>
                </div>

                <!-- Group Actions -->
                <div class="flex flex-wrap items-center gap-1">
                    <button
                        type="button"
                        class="transparent-button text-red-600 disabled:cursor-not-allowed disabled:opacity-50"
                        :disabled="! selectedGroup.id"
                        @click="deleteGroup"
                    >
                        @lang($translation.'.delete-group-btn')
                    </button>

                    <button
                        type="button"
                        class="transparent-button disabled:cursor-not-allowed disabled:opacity-50"
                        :disabled="! selectedGroup.id"
                        @click="editGroup"
                    >
                        @lang($translation.'.edit-group-btn')
                    </button>

                    <button
                        type="button"
                        class="secondary-button"
                        @click="openGroupModal()"
                    >
                        @lang($translation.'.add-group-btn')
                    </button>
                </div>
            </div>

            <!-- Panel Content -->
            <div class="grid grid-cols-3 gap-4 p-4 max-lg:grid-cols-1">
                <!-- Group Columns -->
                <div
                    class="flex h-[calc(100vh-270px)] min-h-96 min-w-0 flex-col rounded-lg border border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-950 max-lg:h-[60vh]"
                    v-for="column in [1, 2]"
                    :key="column"
                >
                    <!-- Column Header -->
                    <div class="flex items-start justify-between gap-2.5 border-b border-gray-200 px-3 py-2.5 dark:border-gray-800">
                        <div class="flex min-w-0 flex-col">
                            <p
                                class="text-sm font-semibold text-gray-800 dark:text-white"
                                v-if="column == 1"
                            >
                                @lang($translation.'.main-column')
                            </p>

                            <p
                                class="text-sm font-semibold text-gray-800 dark:text-white"
                                v-else
                            >
                                @lang($translation.'.right-column')
                            </p>

                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                @lang($translation.'.edit-group-info')
                            </p>
                        </div>

                        <span class="shrink-0 rounded-full bg-gray-200 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                            @{{ columnGroups[column].length }}
                        </span>
                    </div>

                    <!-- Draggable Groups -->
                    <draggable
                        class="flex min-h-0 flex-1 flex-col gap-2.5 overflow-y-auto p-2.5"
                        ghost-class="draggable-ghost"
                        handle=".icon-drag"
                        item-key="id"
                        group="groups"
                        v-bind="{ animation: 200 }"
                        :list="columnGroups[column]"
                    >
                        <template #item="{ element, index }">
                            <div
                                class="shrink-0 overflow-hidden rounded-lg border bg-white transition-all dark:bg-gray-900"
                                :class="isSelected(element)
                                    ? 'border-blue-500 ring-2 ring-blue-500/20'
                                    : 'border-gray-200 dark:border-gray-800'"
                            >
                                <!-- Group Header -->
                                <div
                                    class="group_node flex cursor-pointer items-center gap-1.5 px-2 py-2 transition-all"
                                    :class="isSelected(element)
                                        ? 'bg-blue-50 dark:bg-blue-950/40'
                                        : 'hover:bg-gray-50 dark:hover:bg-gray-800'"
                                    @click.stop="groupSelected(element)"
                                >
                                    <i class="icon-drag shrink-0 cursor-grab text-lg text-gray-400 dark:text-gray-500"></i>

                                    <i
                                        class="shrink-0 text-lg"
                                        :class="[
                                            element.is_user_defined ? 'icon-folder' : 'icon-folder-block',
                                            isSelected(element) ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400',
                                        ]"
                                    ></i>

                                    <span
                                        class="min-w-0 flex-1 text-sm font-semibold wrap-break-word"
                                        :class="isSelected(element) ? 'text-blue-700 dark:text-blue-300' : 'text-gray-800 dark:text-white'"
                                        v-show="editableGroup.id != element.id"
                                    >
                                        @{{ element.admin_name }}

                                        <!-- Rename In Place -->
                                        <button
                                            type="button"
                                            class="icon-edit inline-block cursor-pointer rounded-md py-0.5 align-text-bottom text-sm font-normal text-gray-400 transition-all hover:bg-gray-100 hover:text-gray-700 dark:text-gray-500 dark:hover:bg-gray-800 dark:hover:text-gray-300"
                                            title="@lang($translation.'.edit-group-btn')"
                                            aria-label="@lang($translation.'.edit-group-btn')"
                                            @click.stop="startRenaming(element)"
                                        ></button>
                                    </span>

                                    <input
                                        type="text"
                                        class="group_node min-w-0 flex-1 rounded-sm border border-blue-300 bg-white px-1.5 py-0.5 text-sm font-semibold text-gray-800 outline-hidden dark:border-blue-800 dark:bg-gray-900 dark:text-white"
                                        :name="'attribute_groups[' + element.id + '][admin_name]'"
                                        v-model="element.admin_name"
                                        v-show="editableGroup.id == element.id"
                                        @keydown.enter.prevent="stopRenaming"
                                        @keydown.esc="stopRenaming"
                                    />

                                    <span class="shrink-0 rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                        @{{ element.custom_attributes.length }}
                                    </span>

                                    <span
                                        class="icon-sort-down shrink-0 cursor-pointer rounded-md text-xl text-gray-500 transition-transform hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800"
                                        role="button"
                                        :class="{ 'ltr:-rotate-90 rtl:rotate-90': element.hide }"
                                        @click.stop="element.hide = ! element.hide"
                                    ></span>

                                    <input
                                        type="hidden"
                                        :name="'attribute_groups[' + element.id + '][code]'"
                                        :value="element.code"
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

                                <!-- Group Attributes -->
                                <draggable
                                    class="flex flex-col gap-0.5 border-t border-gray-100 p-1.5 dark:border-gray-800"
                                    ghost-class="draggable-ghost"
                                    handle=".icon-drag"
                                    item-key="id"
                                    group="attributes"
                                    v-bind="{ animation: 200 }"
                                    :list="getGroupAttributes(element)"
                                    :move="onMove"
                                    v-show="! element.hide"
                                    @end="onEnd"
                                >
                                    <template #item="{ element, index }">
                                        <div class="flex items-center gap-2 rounded-md px-2 py-1.5 text-sm text-gray-600 transition-all hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800">
                                            <i class="icon-drag cursor-grab text-lg text-gray-400 dark:text-gray-500"></i>

                                            <i
                                                class="text-lg text-gray-500 dark:text-gray-400"
                                                :class="element.is_user_defined ? 'icon-attribute' : 'icon-attribute-block'"
                                            ></i>

                                            <span class="min-w-0 flex-1 wrap-break-word">
                                                @{{ element.admin_name }}
                                            </span>

                                            <input
                                                type="hidden"
                                                :name="'attribute_groups[' + element.group_id + '][custom_attributes][' + index + '][id]'"
                                                :value="element.id"
                                            />

                                            <input
                                                type="hidden"
                                                :name="'attribute_groups[' + element.group_id + '][custom_attributes][' + index + '][position]'"
                                                :value="index + 1"
                                            />
                                        </div>
                                    </template>

                                    <template #footer>
                                        <p
                                            class="rounded-md border border-dashed border-gray-300 px-2 py-2.5 text-center text-xs text-gray-400 dark:border-gray-700 dark:text-gray-500"
                                            v-if="! element.custom_attributes.length"
                                        >
                                            @lang($translation.'.drop-attributes-here')
                                        </p>
                                    </template>
                                </draggable>
                            </div>
                        </template>
                    </draggable>
                </div>

                <!-- Unassigned Attributes -->
                <div class="flex h-[calc(100vh-270px)] min-h-96 min-w-0 flex-col rounded-lg border border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-950 max-lg:h-[60vh]">
                    <!-- Column Header -->
                    <div class="grid gap-2.5 border-b border-gray-200 px-3 py-2.5 dark:border-gray-800">
                        <div class="flex items-start justify-between gap-2.5">
                            <div class="flex min-w-0 flex-col">
                                <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                    @lang($translation.'.unassigned-attributes')
                                </p>

                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    @lang($translation.'.unassigned-attributes-info')
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-gray-200 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                @{{ unassignedAttributes.length }}
                            </span>
                        </div>

                        <!-- Attribute Search -->
                        <div class="relative">
                            <input
                                type="text"
                                class="block w-full rounded-md border bg-white py-1.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400 ltr:pl-8 ltr:pr-3 rtl:pl-3 rtl:pr-8"
                                placeholder="@lang('admin::app.components.datagrid.toolbar.search.title')"
                                v-model="attributeSearch"
                                @keydown.enter.prevent
                            />

                            <span class="icon-search pointer-events-none absolute top-1/2 -translate-y-1/2 text-xl text-gray-400 ltr:left-2 rtl:right-2"></span>
                        </div>
                    </div>

                    <!-- Draggable Unassigned Attributes -->
                    <draggable
                        id="unassigned-attributes"
                        class="flex min-h-0 flex-1 flex-col gap-0.5 overflow-y-auto p-1.5"
                        ghost-class="draggable-ghost"
                        handle=".icon-drag"
                        item-key="id"
                        group="attributes"
                        v-bind="{ animation: 200 }"
                        :list="visibleUnassignedAttributes"
                    >
                        <template #item="{ element }">
                            <div class="flex items-center gap-2 rounded-md bg-white px-2 py-1.5 text-sm text-gray-600 shadow-xs transition-all hover:bg-gray-50 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800">
                                <i class="icon-drag cursor-grab text-lg text-gray-400 dark:text-gray-500"></i>

                                <i
                                    class="text-lg text-gray-500 dark:text-gray-400"
                                    :class="element.is_user_defined ? 'icon-attribute' : 'icon-attribute-block'"
                                ></i>

                                <span class="min-w-0 flex-1 wrap-break-word">
                                    @{{ element.admin_name }}
                                </span>
                            </div>
                        </template>

                        <template #footer>
                            <p
                                class="rounded-md border border-dashed border-gray-300 px-2 py-2.5 text-center text-xs text-gray-400 dark:border-gray-700 dark:text-gray-500"
                                v-if="! visibleUnassignedAttributes.length"
                            >
                                <template v-if="attributeSearch.trim()">
                                    @lang('admin::app.components.datagrid.filters.dropdown.searchable.no-results')
                                </template>

                                <template v-else>
                                    @lang($translation.'.drop-attributes-here')
                                </template>
                            </p>
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
                                @lang($translation.'.edit-group-title')
                            </p>

                            <p
                                class="text-lg font-bold text-gray-800 dark:text-white"
                                v-else
                            >
                                @lang($translation.'.add-group-title')
                            </p>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content class="max-h-[70vh] overflow-y-auto">
                            <!-- Group Code -->
                            <x-admin::form.control-group v-if="! editingGroup">
                                <x-admin::form.control-group.label class="required">
                                    @lang($translation.'.code')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="code"
                                    rules="required"
                                    :label="trans($translation.'.code')"
                                    :placeholder="trans($translation.'.code')"
                                />

                                <x-admin::form.control-group.error control-name="code" />
                            </x-admin::form.control-group>

                            <!-- Group Admin Name -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang($translation.'.name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="admin_name"
                                    rules="required"
                                    :label="trans($translation.'.name')"
                                    :placeholder="trans($translation.'.name')"
                                />

                                <x-admin::form.control-group.error control-name="admin_name" />
                            </x-admin::form.control-group>

                            <!-- Group Column -->
                            <x-admin::form.control-group v-if="! editingGroup">
                                <x-admin::form.control-group.label class="required">
                                    @lang($translation.'.column')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="column"
                                    rules="required"
                                    :label="trans($translation.'.column')"
                                >
                                    <option value="">
                                        @lang($translation.'.select-group')
                                    </option>

                                    <option value="1">
                                        @lang($translation.'.main-column')
                                    </option>

                                    <option value="2">
                                        @lang($translation.'.right-column')
                                    </option>
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="column" />
                            </x-admin::form.control-group>

                            <!-- Name In Each Locale -->
                            <div class="border-t pt-4 dark:border-gray-800">
                                <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                    @lang($translation.'.group-labels')
                                </p>

                                <p class="mb-4 text-xs font-medium text-gray-500 dark:text-gray-300">
                                    @lang($translation.'.group-labels-info')
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
                            <x-admin::button
                                class="primary-button"
                                :title="trans($translation.'.save-group-btn')"
                                v-if="editingGroup"
                            />

                            <x-admin::button
                                class="primary-button"
                                :title="trans($translation.'.add-group-btn')"
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

            data() {
                return {
                    selectedGroup: { id: null },

                    editableGroup: { id: null },

                    editingGroup: null,

                    attributeSearch: '',

                    addedGroups: 0,

                    dropReverted: false,

                    locales: @json($locales),

                    columnGroups: Object.assign({ 1: [], 2: [] }, @json($columnGroups)),

                    customAttributes: @json($customAttributes),

                    messages: {
                        selectGroup: @json(trans($translation.'.select-group')),
                        removalNotPossible: @json(trans($translation.'.removal-not-possible')),
                        groupCodeExists: @json(trans($translation.'.group-code-already-exists')),
                        groupNameExists: @json(trans($translation.'.group-name-already-exists')),
                        groupHoldsSystemAttributes: @json(trans($translation.'.group-contains-system-attributes')),
                    },
                };
            },

            computed: {
                /**
                 * Every group of the family, from both columns.
                 */
                allGroups() {
                    return Object.values(this.columnGroups).flat();
                },

                /**
                 * Attributes that no group of the family holds yet.
                 */
                unassignedAttributes() {
                    return this.customAttributes.filter((attribute) => ! this.allGroups.some(
                        (group) => group.custom_attributes.some((customAttribute) => customAttribute.id === attribute.id)
                    ));
                },

                /**
                 * The unassigned attributes whose name holds the search term.
                 */
                visibleUnassignedAttributes() {
                    let term = this.attributeSearch.trim().toLowerCase();

                    if (! term) {
                        return this.unassignedAttributes;
                    }

                    return this.unassignedAttributes.filter((attribute) => attribute.admin_name.toLowerCase().includes(term));
                },
            },

            created() {
                window.addEventListener('click', this.handleFocusOut);
            },

            beforeUnmount() {
                window.removeEventListener('click', this.handleFocusOut);
            },

            methods: {
                /**
                 * Whether the given group is the selected one.
                 */
                isSelected(group) {
                    return this.selectedGroup.id == group.id;
                },

                /**
                 * A group's attributes, each tagged with the id of the group.
                 */
                getGroupAttributes(group) {
                    group.custom_attributes.forEach((attribute) => {
                        attribute.group_id = group.id;
                    });

                    return group.custom_attributes;
                },

                /**
                 * Select a group, and make it renameable in place on a second click.
                 */
                groupSelected(group) {
                    if (this.isSelected(group)) {
                        this.startRenaming(group);

                        return;
                    }

                    this.editableGroup = { id: null };

                    this.selectedGroup = group;
                },

                /**
                 * Select a group and turn its name into a field, ready to type into.
                 */
                startRenaming(group) {
                    this.selectedGroup = group;

                    this.editableGroup = group;

                    this.$nextTick(() => this.$el.querySelector(`input[name="attribute_groups[${group.id}][admin_name]"]`)?.focus());
                },

                /**
                 * Leave in-place renaming.
                 */
                stopRenaming() {
                    this.editableGroup = { id: null };
                },

                /**
                 * Open the group popup, empty for a new group or filled from the given one.
                 */
                openGroupModal(group = null) {
                    this.editingGroup = group;

                    let values = { code: group?.code ?? '', admin_name: group?.admin_name ?? '', column: '' };

                    this.locales.forEach((locale) => values['name_' + locale.code] = group?.names[locale.code] ?? '');

                    this.$refs.groupForm.resetForm({ values });

                    this.$refs.groupModal.open();
                },

                /**
                 * Open the group popup for the selected group.
                 */
                editGroup() {
                    if (! this.selectedGroup.id) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: this.messages.selectGroup });

                        return;
                    }

                    this.openGroupModal(this.selectedGroup);
                },

                /**
                 * Apply the popup to the group being edited, or add it as a new group.
                 */
                saveGroup(params, { resetForm, setErrors }) {
                    let names = {};

                    this.locales.forEach((locale) => names[locale.code] = params['name_' + locale.code] ?? '');

                    let sameName = this.findGroup('admin_name', params.admin_name);

                    if (this.editingGroup) {
                        if (
                            sameName
                            && sameName !== this.editingGroup
                        ) {
                            setErrors({ admin_name: [this.messages.groupNameExists] });

                            return;
                        }

                        this.editingGroup.admin_name = params.admin_name;

                        this.editingGroup.names = names;
                    } else {
                        let sameCode = this.findGroup('code', params.code);

                        if (
                            sameCode
                            || sameName
                        ) {
                            setErrors({
                                ...(sameCode ? { code: [this.messages.groupCodeExists] } : {}),
                                ...(sameName ? { admin_name: [this.messages.groupNameExists] } : {}),
                            });

                            return;
                        }

                        this.columnGroups[params.column].push({
                            id: 'group_' + params.column + '_' + (++this.addedGroups),
                            code: params.code,
                            admin_name: params.admin_name,
                            names: names,
                            is_user_defined: 1,
                            custom_attributes: [],
                        });
                    }

                    resetForm();

                    this.$refs.groupModal.close();
                },

                /**
                 * Remove the selected group once confirmed, unless it holds system attributes.
                 */
                deleteGroup() {
                    if (! this.selectedGroup.id) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: this.messages.selectGroup });

                        return;
                    }

                    if (this.selectedGroup.custom_attributes.some((attribute) => ! attribute.is_user_defined)) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: this.messages.groupHoldsSystemAttributes });

                        return;
                    }

                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            for (let groups of Object.values(this.columnGroups)) {
                                let index = groups.indexOf(this.selectedGroup);

                                if (index > -1) {
                                    groups.splice(index, 1);
                                }
                            }

                            this.selectedGroup = { id: null };

                            this.stopRenaming();
                        },
                    });
                },

                /**
                 * The group whose given field holds the given value, if any.
                 */
                findGroup(field, value) {
                    return this.allGroups.find((group) => group[field] == value);
                },

                /**
                 * Refuse a system attribute dragged back to the unassigned list.
                 */
                onMove(event) {
                    this.dropReverted = event.to.id === 'unassigned-attributes'
                        && ! event.draggedContext.element.is_user_defined;

                    return ! this.dropReverted;
                },

                /**
                 * Warn once a drag has been refused.
                 */
                onEnd() {
                    if (this.dropReverted) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: this.messages.removalNotPossible });
                    }
                },

                /**
                 * Leave in-place renaming on a click outside the group name.
                 */
                handleFocusOut(event) {
                    if (! event.target.classList.contains('group_node')) {
                        this.stopRenaming();
                    }
                },
            },
        });
    </script>
@endPushOnce
