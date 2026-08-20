{{-- Renders a section's options from its field schema, for every section type. --}}
<x-admin::appearance.sections.code-editor />

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-section-fields-template"
    >
        <div class="grid min-w-0 gap-4">
            <template v-for="field in schema" :key="field.key">
                <!-- Repeating Rows -->
                <div v-if="field.type === 'repeater'">
                    <p class="mb-2 text-sm font-semibold text-gray-800 dark:text-white">
                        @{{ field.label }}
                    </p>

                    <draggable
                        class="grid gap-3"
                        ghost-class="draggable-ghost"
                        v-bind="{animation: 200}"
                        :list="rowsOf(field)"
                        :item-key="rowKey"
                        handle=".repeater-handle"
                        @end="bubble"
                    >
                        <template #item="{ element: row, index }">
                            <div class="rounded border border-gray-200 p-3 dark:border-gray-800">
                                <div class="mb-2 flex items-center gap-2">
                                    <span class="repeater-handle icon-drag cursor-grab text-lg text-gray-400"></span>

                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-300">
                                        #@{{ index + 1 }}
                                    </span>

                                    <button
                                        type="button"
                                        class="icon-delete cursor-pointer text-xl text-gray-400 hover:text-red-600 ltr:ml-auto rtl:mr-auto"
                                        @click="removeRow(field, index)"
                                    ></button>
                                </div>

                                <v-section-fields
                                    :schema="field.fields"
                                    :model="row"
                                    :section-id="sectionId"
                                    :media-url="mediaUrl"
                                    @change="bubble"
                                ></v-section-fields>
                            </div>
                        </template>
                    </draggable>

                    <button
                        type="button"
                        class="secondary-button mt-2"
                        @click="addRow(field)"
                    >
                        @{{ field.add_label ?? field.label }}
                    </button>
                </div>

                <!-- Key And Value Filters -->
                <div v-else-if="field.type === 'filters'">
                    <p class="mb-2 text-sm font-semibold text-gray-800 dark:text-white">
                        @{{ field.label }}
                    </p>

                    <div class="grid gap-2">
                        <div
                            class="grid gap-1"
                            v-for="(pair, index) in filterPairs"
                            :key="index"
                        >
                            <div class="flex items-center gap-2">
                                <v-select
                                    class="min-w-0 flex-1"
                                    :name="'filter-key-' + index"
                                    :options="keyOptionsFor(field, index).map(option => ({ id: option.value, label: option.label }))"
                                    :value="pair.key"
                                    @update:model-value="key => changeKey(field, pair, key)"
                                ></v-select>

                                <!-- Several categories, stored as the comma separated list the api reads. -->
                                <v-multiselect
                                    class="min-w-0 flex-1"
                                    :name="'filter-' + pair.key"
                                    :options="pickerOptionsFor(field, pair)"
                                    :value="listValue(pair)"
                                    :placeholder="labelFor(field, pair.key)"
                                    v-if="isMultiple(field, pair.key)"
                                    @update:model-value="ids => { pair.value = ids.join(','); syncFilters(field); }"
                                ></v-multiselect>

                                <!-- A fixed set of values is picked, anything else is free text. -->
                                <v-select
                                    class="min-w-0 flex-1"
                                    :name="'filter-' + pair.key"
                                    :options="pickerOptionsFor(field, pair)"
                                    :value="pair.value"
                                    :placeholder="labelFor(field, pair.key)"
                                    v-else-if="optionsFor(field, pair.key).length"
                                    @update:model-value="value => { pair.value = value; syncFilters(field); }"
                                ></v-select>

                                <input
                                    type="text"
                                    class="min-w-0 flex-1 rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                    v-model="pair.value"
                                    v-else
                                    @input="syncFilters(field)"
                                />

                                <button
                                    type="button"
                                    class="icon-delete shrink-0 cursor-pointer text-xl text-gray-400 hover:text-red-600"
                                    @click="removeFilter(field, index)"
                                ></button>
                            </div>
                        </div>
                    </div>

                    <button
                        type="button"
                        class="secondary-button mt-2 disabled:cursor-not-allowed disabled:opacity-50"
                        :disabled="allFiltersUsed(field)"
                        :title="allFiltersUsed(field)
                            ? '@lang('admin::app.appearance.sections.index.all-filters-used')'
                            : ''"
                        @click="addFilter(field)"
                    >
                        @{{ field.add_label ?? field.label }}
                    </button>
                </div>

                <!-- Image -->
                <div v-else-if="field.type === 'image'">
                    <p class="mb-1.5 text-xs font-medium text-gray-800 dark:text-white">
                        @{{ field.label }}
                    </p>

                    <div class="flex items-center gap-3">
                        <img
                            class="h-16 w-24 rounded border border-gray-200 object-cover dark:border-gray-800"
                            :src="imageSrc(field)"
                            v-if="model[field.key]"
                        />

                        <label class="secondary-button cursor-pointer">
                            @lang('admin::app.appearance.sections.edit.image')

                            <input
                                type="file"
                                class="hidden"
                                accept="image/*"
                                @change="upload(field, $event)"
                            />
                        </label>
                    </div>
                </div>

                <!-- Source Code -->
                <div v-else-if="field.type === 'code'">
                    <p class="mb-1.5 text-xs font-medium text-gray-800 dark:text-white">
                        @{{ field.label }}
                    </p>

                    <v-code-editor
                        :language="field.language"
                        :model-value="model[field.key]"
                        :section-id="sectionId"
                        :media-url="mediaUrl"
                        @update:model-value="value => { model[field.key] = value; bubble(); }"
                    ></v-code-editor>
                </div>

                <!-- Whole Number -->
                <div v-else-if="field.type === 'number'">
                    <p class="mb-1.5 text-xs font-medium text-gray-800 dark:text-white">
                        @{{ field.label }}
                    </p>

                    <input
                        type="number"
                        min="0"
                        class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                        v-model="model[field.key]"
                        @input="bubble"
                    />
                </div>

                <!-- Long Text -->
                <div v-else-if="field.type === 'textarea'">
                    <p class="mb-1.5 text-xs font-medium text-gray-800 dark:text-white">
                        @{{ field.label }}
                    </p>

                    <textarea
                        class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                        rows="3"
                        v-model="model[field.key]"
                        @input="bubble"
                    ></textarea>
                </div>

                <!-- Single Line -->
                <div v-else>
                    <p class="mb-1.5 text-xs font-medium text-gray-800 dark:text-white">
                        @{{ field.label }}
                    </p>

                    <input
                        type="text"
                        class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                        v-model="model[field.key]"
                        @input="bubble"
                    />
                </div>
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-section-fields', {
            template: '#v-section-fields-template',

            props: ['schema', 'model', 'sectionId', 'mediaUrl'],

            emits: ['change'],

            data() {
                return {
                    filterPairs: [],

                    rowKeySeed: 0,
                };
            },

            mounted() {
                this.hydrateFilters();
            },

            methods: {
                /**
                 * Rows held by a repeater, creating the list on first use.
                 */
                rowsOf(field) {
                    if (! Array.isArray(this.model[field.key])) {
                        this.model[field.key] = [];
                    }

                    return this.model[field.key];
                },

                /**
                 * A stable identity for a repeater row, so dragging reorders the rows
                 * rather than what the inputs are bound to. Non enumerable, so it stays out
                 * of the JSON posted as the draft.
                 */
                rowKey(row) {
                    if (! row.__key) {
                        Object.defineProperty(row, '__key', {
                            value: `row-${++this.rowKeySeed}`,
                            enumerable: false,
                            configurable: true,
                        });
                    }

                    return row.__key;
                },

                /**
                 * Filters still free, plus the one this row already holds.
                 *
                 * The stored value is a map keyed by filter, so two rows on the same
                 * filter would collapse into one and quietly lose an entry.
                 */
                keyOptionsFor(field, index) {
                    const taken = this.filterPairs
                        .filter((pair, position) => position !== index)
                        .map(pair => pair.key);

                    return (field.keys ?? []).filter(option => ! taken.includes(option.value));
                },

                /**
                 * Whether every filter this section supports is already in the list.
                 */
                allFiltersUsed(field) {
                    return this.filterPairs.length >= (field.keys ?? []).length;
                },

                /**
                 * The values a given filter accepts, empty when it takes free text.
                 */
                optionsFor(field, key) {
                    return (field.keys ?? []).find(option => option.value === key)?.options ?? [];
                },

                /**
                 * The choices a value select shows, including whatever is already stored so
                 * a value the list no longer offers still displays instead of reading blank.
                 */
                valueOptionsFor(field, pair) {
                    const options = this.optionsFor(field, pair.key);

                    if (
                        ! options.length
                        || pair.value === ''
                        || options.some(option => option.value === pair.value)
                    ) {
                        return options;
                    }

                    return [{ value: pair.value, label: pair.value }, ...options];
                },

                /**
                 * Whether a filter holds several values at once.
                 */
                isMultiple(field, key) {
                    return (field.keys ?? []).find(option => option.value === key)?.multiple === true;
                },

                /**
                 * The stored comma separated value as the list the picker expects.
                 */
                listValue(pair) {
                    return String(pair.value ?? '')
                        .split(',')
                        .map(id => id.trim())
                        .filter(Boolean);
                },

                /**
                 * Point a filter at a different key, starting it on a value that key accepts.
                 */
                changeKey(field, pair, key) {
                    pair.key = key;

                    pair.value = this.optionsFor(field, key)[0]?.value ?? '';

                    this.syncFilters(field);
                },

                /**
                 * The same choices keyed the way the shared picker reads them.
                 */
                pickerOptionsFor(field, pair) {
                    return this.valueOptionsFor(field, pair).map(option => ({
                        id: option.value,
                        label: option.label,
                    }));
                },

                /**
                 * A filter's own label, used where the control needs a placeholder.
                 */
                labelFor(field, key) {
                    return (field.keys ?? []).find(option => option.value === key)?.label ?? '';
                },

                /**
                 * Append an empty row to a repeater.
                 */
                addRow(field) {
                    const row = {};

                    field.fields.forEach(child => row[child.key] = '');

                    this.rowsOf(field).push(row);

                    this.bubble();
                },

                /**
                 * Drop one row from a repeater.
                 */
                removeRow(field, index) {
                    this.rowsOf(field).splice(index, 1);

                    this.bubble();
                },

                /**
                 * Turn the stored filter map into editable pairs, reading values back as
                 * strings so they match the option values they are compared against.
                 */
                hydrateFilters() {
                    const field = (this.schema ?? []).find(item => item.type === 'filters');

                    if (! field) {
                        return;
                    }

                    const stored = this.model[field.key] ?? {};

                    this.filterPairs = Object.keys(stored).map(key => ({
                        key,
                        value: stored[key] === null || stored[key] === undefined ? '' : String(stored[key]),
                    }));

                    this.filterPairs.forEach(pair => {
                        const options = this.optionsFor(field, pair.key);

                        if (
                            ! options.length
                            || options.some(option => option.value === pair.value)
                        ) {
                            return;
                        }

                        if (pair.value === '') {
                            pair.value = options[0].value;
                        }
                    });

                    this.model[field.key] = this.filtersMap();
                },

                /**
                 * Write the editable pairs back as the map the storefront reads.
                 */
                syncFilters(field) {
                    this.model[field.key] = this.filtersMap();

                    this.bubble();
                },

                /**
                 * The editable pairs as the map the storefront reads.
                 */
                filtersMap() {
                    const map = {};

                    this.filterPairs.forEach(pair => {
                        if (pair.key) {
                            map[pair.key] = pair.value;
                        }
                    });

                    return map;
                },

                /**
                 * Start a new filter on the first key that is not in use yet.
                 */
                addFilter(field) {
                    const used = this.filterPairs.map(pair => pair.key);

                    const next = (field.keys ?? []).find(option => ! used.includes(option.value));

                    if (! next) {
                        return;
                    }

                    this.filterPairs.push({
                        key: next.value,
                        value: next.options?.[0]?.value ?? '',
                    });

                    this.syncFilters(field);
                },

                /**
                 * Drop one filter.
                 */
                removeFilter(field, index) {
                    this.filterPairs.splice(index, 1);

                    this.syncFilters(field);
                },

                /**
                 * Browser reachable url for a stored image path.
                 */
                imageSrc(field) {
                    const path = this.model[field.key];

                    return path?.startsWith('http') ? path : '/' + path;
                },

                /**
                 * Upload a replacement image and record the path it was stored at.
                 */
                upload(field, event) {
                    const file = event.target.files?.[0];

                    if (! file) {
                        return;
                    }

                    const payload = new FormData();

                    payload.append('file', file);

                    this.$axios.post(this.mediaUrl.replace('__ID__', this.sectionId), payload)
                        .then(response => {
                            this.model[field.key] = response.data.path;

                            this.bubble();
                        })
                        .catch(error => this.$emitter.emit('add-flash', {
                            type: 'error',
                            message: error.response?.data?.message ?? error.message,
                        }));
                },

                /**
                 * Tell the editor something changed, so it can save a draft.
                 */
                bubble() {
                    this.$emit('change');
                },
            },
        });
    </script>
@endPushOnce
