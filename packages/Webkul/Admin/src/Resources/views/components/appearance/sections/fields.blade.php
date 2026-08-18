{{--
    Renders a section's options from its field schema. One component covers every
    section type, so it can live inside the editor drawer where a per-type view could
    not: those register their own Vue components through the layout's script stack.
--}}
<x-admin::appearance.sections.code-editor />

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-section-fields-template"
    >
        <div class="grid gap-4">
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
                                <select
                                    class="w-1/2 custom-select rounded-md border bg-white px-3 py-2.5 text-sm font-normal text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                    v-model="pair.key"
                                    @change="syncFilters(field)"
                                >
                                    <option
                                        v-for="option in keyOptionsFor(field, index)"
                                        :key="option.value"
                                        :value="option.value"
                                        v-text="option.label"
                                    ></option>
                                </select>

                                <!-- A known set of values picks from a list, anything else is free text. -->
                                <select
                                    class="w-1/2 custom-select rounded-md border bg-white px-3 py-2.5 text-sm font-normal text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                    v-model="pair.value"
                                    v-if="optionsFor(field, pair.key).length"
                                    @change="syncFilters(field)"
                                >
                                    <option
                                        v-for="option in valueOptionsFor(field, pair)"
                                        :key="option.value"
                                        :value="option.value"
                                        v-text="option.label"
                                    ></option>
                                </select>

                                <input
                                    :type="inputTypeFor(field, pair.key)"
                                    min="1"
                                    class="w-1/2 rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                    v-model="pair.value"
                                    v-else
                                    @input="syncFilters(field)"
                                />

                                <button
                                    type="button"
                                    class="icon-delete cursor-pointer text-xl text-gray-400 hover:text-red-600"
                                    @click="removeFilter(field, index)"
                                ></button>
                            </div>

                            <p
                                class="text-xs text-gray-500 dark:text-gray-300"
                                v-if="hintFor(field, pair.key)"
                                v-text="hintFor(field, pair.key)"
                            ></p>
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
                 * rather than shuffling what the inputs are bound to.
                 *
                 * Defined as non enumerable, which keeps it out of the JSON posted as the
                 * draft; the row is stored exactly as the storefront expects it.
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
                 * The control a filter value needs, so a count cannot be typed as words.
                 */
                inputTypeFor(field, key) {
                    return (field.keys ?? []).find(option => option.value === key)?.input ?? 'text';
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
                 * Guidance for a filter that needs it.
                 */
                hintFor(field, key) {
                    return (field.keys ?? []).find(option => option.value === key)?.hint ?? '';
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
                 * Turn the stored filter map into editable pairs.
                 *
                 * Values are read back as strings because they are compared against option
                 * values, and a number stored as 12 would not match the option "12" — the
                 * select would then sit blank on a value that is actually set.
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

                        /**
                         * Nothing offered matches what is stored. An empty value falls back
                         * to the first choice; anything else is kept and offered alongside
                         * them, so a value set before the list changed is not thrown away
                         * without the operator seeing it.
                         */
                        pair.value = pair.value === '' ? options[0].value : pair.value;
                    });

                    this.syncFilters(field);
                },

                /**
                 * Write the editable pairs back as the map the storefront reads.
                 */
                syncFilters(field) {
                    const map = {};

                    this.filterPairs.forEach(pair => {
                        if (pair.key) {
                            map[pair.key] = pair.value;
                        }
                    });

                    this.model[field.key] = map;

                    this.bubble();
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
