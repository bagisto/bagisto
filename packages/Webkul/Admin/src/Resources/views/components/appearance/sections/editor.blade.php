@props([
    'sections'    => [],
    'typeLabels'  => [],
    'themeName'   => null,
    'channels'    => [],
    'channelId'   => null,
    'locales'     => [],
    'localeCode'  => null,
    'previewUrl'  => '',
    'reorderUrl'  => '',
    'storeUrl'    => '',
    'publishUrl'  => '',
    'discardUrl'  => '',
    'urls'        => [],
])

<v-section-editor
    :sections='@json($sections)'
    :type-labels='@json($typeLabels)'
    :channels='@json($channels)'
    :locales='@json($locales)'
    publish-url="{{ $publishUrl }}"
    discard-url="{{ $discardUrl }}"
    :urls='@json($urls)'
    channel-id="{{ $channelId }}"
    locale-code="{{ $localeCode }}"
    preview-url="{{ $previewUrl }}"
    reorder-url="{{ $reorderUrl }}"
    store-url="{{ $storeUrl }}"
    theme-name="{{ $themeName }}"
>
    <x-admin::shimmer.appearance.sections />
</v-section-editor>

<x-admin::appearance.sections.fields />

{{-- Wrapped, because the component's own class attribute wins over one passed in. --}}
<div class="hidden">
    <x-admin::form.control-group.advance.select name="section-filter-picker" />

    <x-admin::form.control-group.advance.multiselect name="section-filter-multi-picker" />
</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-section-editor-template"
    >
        <div class="flex h-[calc(100vh-140px)] min-h-[480px] flex-col">
            <!-- Page Header -->
            <div class="flex shrink-0 items-center justify-between gap-4 max-sm:flex-wrap">
                <div class="grid gap-1.5">
                    <p class="text-xl font-bold text-gray-800 dark:text-white">
                        @lang('admin::app.appearance.sections.index.title')
                    </p>

                    <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                        @lang('admin::app.appearance.sections.index.scoped-to', [
                            'theme'   => '@{{ themeName }}',
                            'channel' => '@{{ channelName }}',
                        ])
                    </p>
                </div>

                <div class="flex items-center gap-2.5 max-sm:w-full max-sm:flex-wrap">
                    <!-- Locale Switcher -->
                    <select
                        class="custom-select cursor-pointer rounded-md border bg-white px-3 py-2.5 text-sm font-normal text-gray-600 transition-all hover:border-gray-400 ltr:pr-8 rtl:pl-8 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                        :title="'@lang('admin::app.appearance.sections.index.locale')'"
                        v-model="locale"
                        @change="switchLocale"
                        v-if="locales.length > 1"
                    >
                        <option
                            v-for="option in locales"
                            :key="option.code"
                            :value="option.code"
                            v-text="option.name"
                        ></option>
                    </select>

                    <!-- Channel Switcher -->
                    <select
                        class="custom-select cursor-pointer rounded-md border bg-white px-3 py-2.5 text-sm font-normal text-gray-600 transition-all hover:border-gray-400 ltr:pr-8 rtl:pl-8 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                        :title="'@lang('admin::app.appearance.sections.index.channel')'"
                        v-model="channel"
                        @change="switchChannel"
                        v-if="channels.length > 1"
                    >
                        <option
                            v-for="option in channels"
                            :key="option.id"
                            :value="option.id"
                            v-text="option.name"
                        ></option>
                    </select>

                    <!-- Unpublished Changes -->
                    <template v-if="pendingCount">
                        <button
                            type="button"
                            class="secondary-button"
                            :disabled="isBusy"
                            @click="discardAll"
                        >
                            @lang('admin::app.appearance.sections.index.discard-btn')
                        </button>

                        <button
                            type="button"
                            class="primary-button"
                            :disabled="isBusy"
                            @click="publishAll"
                        >
                            @lang('admin::app.appearance.sections.index.publish-btn') (@{{ pendingCount }})
                        </button>
                    </template>
                </div>
            </div>

            <div class="mt-4 flex min-h-0 min-w-0 flex-1">
                <div class="flex min-h-0 min-w-0 flex-1 gap-4 max-lg:flex-col">
                    <!-- Section List -->
                    <div class="box-shadow flex w-[340px] shrink-0 flex-col overflow-hidden rounded bg-white dark:bg-gray-900 max-lg:w-full">
                        <div class="flex shrink-0 items-center gap-2 border-b p-4 dark:border-gray-800">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.components.layouts.sidebar.sections')
                            </p>

                            @if (bouncer()->hasPermission('appearance.sections.create'))
                                <button
                                    type="button"
                                    class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white transition-all hover:bg-blue-700"
                                    :title="'@lang('admin::app.appearance.sections.index.create-btn')'"
                                    @click="openCreate"
                                >
                                    {{-- Drawn rather than set as a glyph, so it sits dead centre in the circle. --}}
                                    <svg
                                        class="h-3 w-3"
                                        viewBox="0 0 12 12"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        aria-hidden="true"
                                    >
                                        <path d="M6 1.5v9M1.5 6h9" />
                                    </svg>
                                </button>
                            @endif

                            <span
                                class="label-pending ltr:ml-auto rtl:mr-auto"
                                v-if="pendingCount"
                            >
                                @{{ pendingCount }} @lang('admin::app.appearance.sections.index.unsaved')
                            </span>
                        </div>

                        <div class="min-h-0 flex-1 overflow-y-auto">
                            <draggable
                                class="flex flex-col"
                                ghost-class="draggable-ghost"
                                v-bind="{animation: 200}"
                                :list="items"
                                item-key="id"
                                handle=".section-handle"
                                :move="canMove"
                                @end="persistOrder"
                            >
                                <template #item="{ element }">
                                    <div
                                        class="flex items-center gap-2 border-b px-3 py-2.5 transition-all dark:border-gray-800"
                                        :class="element.id === activeId ? 'bg-blue-50 dark:bg-gray-950' : 'hover:bg-gray-50 dark:hover:bg-gray-950'"
                                    >
                                        <span
                                            class="section-handle icon-drag cursor-grab text-xl text-gray-400"
                                            v-if="! element.is_pinned"
                                        ></span>

                                        <span
                                            class="w-5 shrink-0 text-center text-xs text-gray-300"
                                            :title="'@lang('admin::app.appearance.sections.index.pinned')'"
                                            v-else
                                        >&mdash;</span>

                                        <button
                                            type="button"
                                            class="min-w-0 flex-1 text-left ltr:pr-1 rtl:pl-1"
                                            @click="select(element)"
                                        >
                                            <span class="flex items-center gap-1">
                                                <span
                                                    class="truncate text-sm font-medium"
                                                    :class="element.status ? 'text-gray-800 dark:text-white' : 'text-gray-400 line-through dark:text-gray-500'"
                                                >
                                                    @{{ element.name }}
                                                </span>

                                                <span
                                                    class="icon-dot shrink-0 text-lg text-orange-500"
                                                    :title="'@lang('admin::app.appearance.sections.index.unsaved')'"
                                                    v-if="element.has_draft"
                                                ></span>
                                            </span>

                                            <span class="block truncate text-xs text-gray-500 dark:text-gray-300">
                                                @{{ typeLabel(element.type) }}
                                            </span>
                                        </button>

                                        <!--
                                            A labelled switch rather than an icon: the section list is the
                                            only place a hidden section can be brought back, so the control
                                            has to read the same in both states.
                                        -->
                                        <button
                                            type="button"
                                            class="relative h-5 w-9 shrink-0 rounded-full transition-all"
                                            :class="element.status ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-800'"
                                            :title="element.status
                                                ? '@lang('admin::app.appearance.sections.edit.active')'
                                                : '@lang('admin::app.appearance.sections.edit.inactive')'"
                                            @click.stop="toggleStatus(element)"
                                        >
                                            <span
                                                class="absolute top-0.5 h-4 w-4 rounded-full border border-gray-300 bg-white transition-all ltr:left-0.5 rtl:right-0.5"
                                                :class="element.status ? 'ltr:translate-x-full rtl:-translate-x-full' : ''"
                                            ></span>
                                        </button>

                                        <x-admin::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'rtl' ? 'left' : 'right' }}">
                                            <x-slot:toggle>
                                                <button
                                                    type="button"
                                                    class="icon-dots shrink-0 text-xl text-gray-400 hover:text-gray-700 dark:hover:text-white"
                                                ></button>
                                            </x-slot>

                                            <x-slot:menu>
                                                @if (bouncer()->hasPermission('appearance.sections.create'))
                                                    <x-admin::dropdown.menu.item
                                                        v-if="! element.is_pinned"
                                                        @click="duplicate(element)"
                                                    >
                                                        @lang('admin::app.appearance.sections.index.duplicate-btn')
                                                    </x-admin::dropdown.menu.item>
                                                @endif

                                                @if (bouncer()->hasPermission('appearance.sections.delete'))
                                                    <x-admin::dropdown.menu.item @click="remove(element)">
                                                        @lang('admin::app.appearance.sections.edit.delete')
                                                    </x-admin::dropdown.menu.item>
                                                @endif
                                            </x-slot>
                                        </x-admin::dropdown>
                                    </div>
                                </template>
                            </draggable>

                            <p
                                class="p-4 text-sm text-gray-500 dark:text-gray-300"
                                v-if="! items.length"
                            >
                                @lang('admin::app.appearance.sections.index.empty')
                            </p>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div
                        class="box-shadow flex min-w-0 flex-1 flex-col overflow-hidden rounded bg-white dark:bg-gray-900"
                        ref="previewPane"
                    >
                        <div class="flex shrink-0 items-center justify-between gap-2 border-b p-4 dark:border-gray-800">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                @lang('admin::app.appearance.sections.index.preview-btn')
                            </p>

                            <!-- Device Widths -->
                            <div class="flex items-center gap-1">
                                <button
                                    type="button"
                                    class="rounded px-2.5 py-1 text-xs font-medium transition-all"
                                    :class="device === option.key
                                        ? 'bg-blue-50 text-blue-700 dark:bg-gray-950 dark:text-blue-400'
                                        : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-950'"
                                    v-for="option in devices"
                                    :key="option.key"
                                    @click="device = option.key"
                                    v-text="option.label"
                                ></button>

                                <button
                                    type="button"
                                    class="icon-repeat flex h-6 w-6 items-center justify-center rounded text-base text-gray-500 transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                                    :title="'@lang('admin::app.appearance.sections.edit.preview')'"
                                    @click="reloadPreview"
                                ></button>
                            </div>
                        </div>

                        <div
                            class="relative min-h-0 flex-1 overflow-hidden bg-gray-100 p-4 dark:bg-gray-950"
                            ref="previewStage"
                        >
                            <div
                                class="absolute overflow-hidden rounded bg-white transition-all duration-300 ease-in-out"
                                :style="frameBoxStyle"
                            >
                                <iframe
                                    class="border-0 bg-white"
                                    ref="preview"
                                    :src="previewUrl"
                                    :style="frameStyle"
                                    @load="onPreviewLoad"
                                ></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Editor Drawer -->
            <x-admin::drawer
                ref="editDrawer"
                width="480px"
                @open="onDrawerOpen"
                @close="onDrawerClose"
            >
                <x-slot:header class="p-4">
                    <p class="text-lg font-bold text-gray-800 dark:text-white">
                        @{{ active?.name }}
                    </p>

                    <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                        @{{ typeLabel(active?.type) }}
                    </p>
                </x-slot>

                <x-slot:content class="p-4">
                    <v-section-fields
                        :schema="fields"
                        :model="options"
                        :section-id="active?.id"
                        :media-url="urls.media"
                        :key="active?.id"
                        @change="queueDraft"
                        v-if="fields.length"
                    ></v-section-fields>

                    <p
                        class="text-sm text-gray-500 dark:text-gray-300"
                        v-else
                    >
                        @lang('admin::app.appearance.sections.index.empty')
                    </p>
                </x-slot>

            </x-admin::drawer>

            <!-- Create Drawer -->
            <x-admin::drawer
                ref="createDrawer"
                width="480px"
            >
                <x-slot:header class="p-4">
                    <p class="text-lg font-bold text-gray-800 dark:text-white">
                        @lang('admin::app.appearance.sections.create.title')
                    </p>
                </x-slot>

                <x-slot:content class="p-4">
                    <x-admin::form
                        v-slot="{ meta, errors, handleSubmit }"
                        as="div"
                    >
                        <form
                            id="section-create-form"
                            @submit="handleSubmit($event, create)"
                        >
                            <!-- Type -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.appearance.sections.create.type.title')
                                </x-admin::form.control-group.label>

                                <div class="grid grid-cols-3 gap-2 max-sm:grid-cols-2">
                                    <button
                                        type="button"
                                        class="flex flex-col items-center justify-center gap-1.5 rounded border p-3 text-center transition-all"
                                        :class="newType === option.key
                                            ? 'border-blue-600 bg-blue-50 text-blue-700 dark:border-blue-600 dark:bg-gray-950 dark:text-blue-400'
                                            : 'border-gray-200 text-gray-600 hover:border-gray-400 dark:border-gray-800 dark:text-gray-300'"
                                        v-for="option in creatableTypes"
                                        :key="option.key"
                                        @click="newType = option.key"
                                    >
                                        <span
                                            class="text-2xl"
                                            :class="option.icon"
                                        ></span>

                                        <span
                                            class="text-xs font-medium"
                                            v-text="option.label"
                                        ></span>
                                    </button>
                                </div>

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="type"
                                    rules="required"
                                    v-model="newType"
                                    :label="trans('admin::app.appearance.sections.create.type.title')"
                                />

                                <x-admin::form.control-group.error control-name="type" />
                            </x-admin::form.control-group>

                            <!-- Name -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.appearance.sections.create.name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="name"
                                    rules="required"
                                    :label="trans('admin::app.appearance.sections.create.name')"
                                    :placeholder="trans('admin::app.appearance.sections.create.name')"
                                />

                                <x-admin::form.control-group.error control-name="name" />
                            </x-admin::form.control-group>
                        </form>
                    </x-admin::form>
                </x-slot>

                <x-slot:footer class="px-4 pb-8">
                    <div class="flex items-center justify-end">
                        <button
                            type="submit"
                            form="section-create-form"
                            class="primary-button"
                            :disabled="isBusy"
                        >
                            @lang('admin::app.appearance.sections.create.save-btn')
                        </button>
                    </div>
                </x-slot>
            </x-admin::drawer>
        </div>
    </script>


    <script type="module">
        app.component('v-section-editor', {
            template: '#v-section-editor-template',

            props: [
                'sections',
                'typeLabels',
                'channels',
                'channelId',
                'locales',
                'localeCode',
                'previewUrl',
                'reorderUrl',
                'storeUrl',
                'publishUrl',
                'discardUrl',
                'urls',
                'themeName',
            ],

            mounted() {
                window.addEventListener('message', this.onPreviewMessage);

                this.watchStage();

                this.openRequestedSection();
            },

            beforeUnmount() {
                window.removeEventListener('message', this.onPreviewMessage);

                this.stageObserver?.disconnect();
            },

            data() {
                return {
                    items: this.sections ?? [],

                    channel: Number(this.channelId),

                    locale: this.localeCode,

                    activeId: null,

                    device: 'desktop',

                    stage: { width: 0, height: 0 },

                    stagePadding: 16,

                    isBusy: false,

                    isPanelOpen: false,

                    previewOrigin: null,

                    previewRetries: 0,

                    drawerWidth: 480,

                    active: null,

                    fields: [],

                    options: {},

                    newType: 'product_carousel',

                    draftTimer: null,

                    devices: [
                        { key: 'desktop', label: "@lang('admin::app.appearance.sections.index.desktop')" },
                        { key: 'tablet', label: "@lang('admin::app.appearance.sections.index.tablet')" },
                        { key: 'mobile', label: "@lang('admin::app.appearance.sections.index.mobile')" },
                    ],

                    sectionTypes: [
                        {
                            key: 'product_carousel',
                            icon: 'icon-product',
                            label: "@lang('admin::app.appearance.sections.create.type.product-carousel')",
                        },
                        {
                            key: 'category_carousel',
                            icon: 'icon-folder',
                            label: "@lang('admin::app.appearance.sections.create.type.category-carousel')",
                        },
                        {
                            key: 'image_carousel',
                            icon: 'icon-image',
                            label: "@lang('admin::app.appearance.sections.create.type.image-carousel')",
                        },
                        {
                            key: 'static_content',
                            icon: 'icon-cms',
                            label: "@lang('admin::app.appearance.sections.create.type.static-content')",
                        },
                        {
                            key: 'footer_links',
                            icon: 'icon-list',
                            label: "@lang('admin::app.appearance.sections.create.type.footer-links')",
                        },
                        {
                            key: 'services_content',
                            icon: 'icon-store',
                            label: "@lang('admin::app.appearance.sections.create.type.services-content')",
                        },
                    ],
                };
            },

            computed: {
                /**
                 * How many sections hold edits that are not published yet.
                 */
                pendingCount() {
                    return this.items.filter(section => section.has_draft).length;
                },

                /**
                 * Path the frame is supposed to be sitting on.
                 */
                previewPath() {
                    return new URL(this.previewUrl, window.location.origin).pathname;
                },

                /**
                 * Name of the channel being edited, for the header line.
                 */
                channelName() {
                    return this.channels.find(option => option.id === this.channel)?.name ?? '';
                },

                /**
                 * Types a new section may take. A channel renders one footer at the bottom
                 * of the page, so a second one has nowhere to go.
                 */
                creatableTypes() {
                    const taken = this.items.some(section => section.is_pinned);

                    return this.sectionTypes.filter(option => ! taken || option.key !== 'footer_links');
                },

                /**
                 * Viewport width the chosen device stands for. The frame is rendered at this
                 * width whatever room the editor has, so the storefront lays itself out the
                 * way it would on the device rather than the way it fits the panel.
                 */
                frameWidth() {
                    return { desktop: 1440, tablet: 768, mobile: 390 }[this.device];
                },

                /**
                 * How far the frame has to shrink to fit the room it is given, never past
                 * its own size so a narrow device is not blown up.
                 */
                frameScale() {
                    if (! this.stage.width) {
                        return 1;
                    }

                    return Math.min(1, this.stage.width / this.frameWidth);
                },

                /**
                 * The space the scaled frame takes up in the editor.
                 */
                frameBoxStyle() {
                    const width = Math.round(this.frameWidth * this.frameScale);

                    return {
                        width: `${width}px`,
                        top: `${this.stagePadding}px`,
                        bottom: `${this.stagePadding}px`,
                        insetInlineStart: '50%',
                        marginInlineStart: `-${Math.round(width / 2)}px`,
                    };
                },

                /**
                 * The frame itself, drawn at device size and scaled down into its box.
                 */
                frameStyle() {
                    return {
                        width: `${this.frameWidth}px`,
                        height: `${this.stage.height / this.frameScale}px`,
                        transform: `scale(${this.frameScale})`,
                        transformOrigin: 'top left',
                    };
                },
            },

            methods: {
                /**
                 * Human readable name for a section type.
                 */
                typeLabel(type) {
                    return this.typeLabels[type] ?? type;
                },

                /**
                 * Reload the editor against another channel. Channels keep their own
                 * sections and may run a different theme, so this follows the theme that
                 * channel actually runs rather than staying on this one.
                 */
                switchChannel() {
                    const target = this.channels.find(option => option.id === this.channel);

                    if (target) {
                        window.location.href = target.url;
                    }
                },

                /**
                 * Reload the editor against another locale. A section's content is stored
                 * per locale, so the whole editor and its preview move together.
                 */
                switchLocale() {
                    const target = this.locales.find(option => option.code === this.locale);

                    if (target) {
                        window.location.href = target.url;
                    }
                },

                /**
                 * Open a section for editing.
                 */
                select(section) {
                    this.activeId = section.id;

                    this.active = section;

                    this.fields = [];

                    this.options = {};

                    this.$axios.get(this.actionFor(section.id, 'fields'))
                        .then(response => {
                            this.fields = response.data.schema;

                            this.options = response.data.options ?? {};

                            this.$refs.editDrawer.open();

                            this.focusInPreview(section.id);
                        })
                        .catch(error => this.sectionError(error, section));
                },

                /**
                 * Open the section named in the url, so that creating one lands straight on it.
                 */
                openRequestedSection() {
                    const requested = Number(new URLSearchParams(window.location.search).get('section'));

                    const section = this.items.find(item => item.id === requested);

                    if (section) {
                        this.select(section);
                    }
                },

                /**
                 * Start a new section.
                 */
                openCreate() {
                    this.newType = this.creatableTypes[0]?.key ?? 'product_carousel';

                    this.$refs.createDrawer.open();
                },

                /**
                 * Create a section against the theme and channel already being edited, then
                 * open it straight away.
                 */
                create(params, { resetForm }) {
                    this.isBusy = true;

                    this.$axios.post(this.storeUrl, params)
                        .then(response => {
                            this.insertSection(response.data.section);

                            this.$refs.createDrawer.close();

                            resetForm();

                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: response.data.message,
                            });

                            this.reloadPreview();

                            this.select(response.data.section);
                        })
                        .catch(error => this.flashError(error))
                        .finally(() => this.isBusy = false);
                },

                /**
                 * Grow the preview over the whole screen except the drawer, so edits are
                 * checked at full size rather than in a column.
                 */
                onDrawerOpen() {
                    this.isPanelOpen = true;

                    this.$nextTick(() => this.expandPreview());
                },

                /**
                 * Put the preview back where it belongs.
                 */
                onDrawerClose() {
                    this.isPanelOpen = false;

                    this.focusInPreview(null);

                    this.collapsePreview();
                },

                /**
                 * Lift the preview out of the page flow and fill the screen beside the
                 * drawer, transitioning from its current box so that it grows rather than
                 * jumps.
                 */
                expandPreview() {
                    const pane = this.$refs.previewPane;

                    if (
                        ! pane
                        || window.innerWidth < 1024
                    ) {
                        return;
                    }

                    const box = pane.getBoundingClientRect();

                    this.previewOrigin = {
                        top: box.top,
                        left: box.left,
                        width: box.width,
                        height: box.height,
                    };

                    Object.assign(pane.style, {
                        position: 'fixed',
                        margin: '0',
                        zIndex: '10002',
                        borderRadius: '0',
                        transition: 'none',
                        top: `${box.top}px`,
                        left: `${box.left}px`,
                        width: `${box.width}px`,
                        height: `${box.height}px`,
                    });

                    this.commitStartingBox(pane);

                    const rtl = document.documentElement.dir === 'rtl';

                    Object.assign(pane.style, {
                        transition: 'top .3s ease-in-out, left .3s ease-in-out, width .3s ease-in-out, height .3s ease-in-out',
                        top: '0px',
                        left: rtl ? `${this.drawerWidth}px` : '0px',
                        width: `${window.innerWidth - this.drawerWidth}px`,
                        height: `${window.innerHeight}px`,
                    });
                },

                /**
                 * Commit the box just written by reading a layout property, so the browser
                 * does not collapse both style writes into one and skip the transition.
                 */
                commitStartingBox(element) {
                    void element.offsetWidth;
                },

                /**
                 * Shrink the preview back to the box it came from, then hand it back to
                 * the page flow.
                 */
                collapsePreview() {
                    const pane = this.$refs.previewPane;

                    const origin = this.previewOrigin;

                    if (
                        ! pane
                        || ! origin
                    ) {
                        return;
                    }

                    this.previewOrigin = null;

                    const restore = () => {
                        pane.removeAttribute('style');

                        pane.removeEventListener('transitionend', restore);
                    };

                    pane.addEventListener('transitionend', restore);

                    setTimeout(restore, 400);

                    Object.assign(pane.style, {
                        top: `${origin.top}px`,
                        left: `${origin.left}px`,
                        width: `${origin.width}px`,
                        height: `${origin.height}px`,
                    });
                },

                /**
                 * Re-apply the highlight a reload wipes, and put the frame back if it has
                 * been navigated off the preview.
                 */
                onPreviewLoad() {
                    const frame = this.$refs.preview;

                    let path = null;

                    try {
                        path = frame.contentWindow.location.pathname;
                    } catch (error) {
                        path = null;
                    }

                    if (
                        path !== this.previewPath
                        && this.previewRetries < 3
                    ) {
                        this.previewRetries++;

                        frame.src = this.previewUrl;

                        return;
                    }

                    this.previewRetries = 0;

                    this.focusInPreview(this.isPanelOpen ? this.activeId : null);
                },

                /**
                 * Add a section to the list above the pinned ones, matching the order the
                 * server just wrote.
                 */
                insertSection(section) {
                    const pinned = this.items.findIndex(item => item.is_pinned);

                    this.items.splice(pinned === -1 ? this.items.length : pinned, 0, section);
                },

                /**
                 * Ask the preview to highlight a section and scroll it into view. A null
                 * id clears the highlight, so the preview is not left dimmed once editing
                 * stops.
                 */
                focusInPreview(id) {
                    this.$refs.preview?.contentWindow?.postMessage(
                        { type: 'section-focus', id },
                        window.location.origin
                    );
                },

                /**
                 * Switch the drawer to the section a user clicked inside the preview, only
                 * while it is already open. Editing starts from the section list.
                 */
                onPreviewMessage(event) {
                    if (
                        event.origin !== window.location.origin
                        || event.data?.type !== 'section-selected'
                        || ! this.isPanelOpen
                    ) {
                        return;
                    }

                    const section = this.items.find(item => item.id === event.data.id);

                    if (section) {
                        this.select(section);
                    }
                },

                /**
                 * Persist the open section's edits as a draft, then refresh the preview.
                 * Debounced so that typing does not post on every keystroke.
                 */
                queueDraft() {
                    clearTimeout(this.draftTimer);

                    this.draftTimer = setTimeout(() => this.saveDraft(), 600);
                },

                /**
                 * Write the current options to the section's draft.
                 */
                saveDraft() {
                    if (! this.active) {
                        return;
                    }

                    this.$axios.post(this.actionFor(this.active.id, 'draft'), { options: this.options })
                        .then(response => {
                            this.active.has_draft = response.data.has_draft;

                            this.reloadPreview();
                        })
                        .catch(error => this.sectionError(error, this.active));
                },

                /**
                 * Whether a drag may complete. A pinned section renders at a fixed place on
                 * the page, so neither it nor the slot it occupies can take part.
                 */
                canMove(event) {
                    return ! event.draggedContext.element?.is_pinned
                        && ! event.relatedContext.element?.is_pinned;
                },

                /**
                 * Persist the order after a drag, then refresh the preview so the frame
                 * matches the list.
                 */
                persistOrder() {
                    this.$axios.post(this.reorderUrl, { sections: this.items.map(section => section.id) })
                        .then(response => {
                            this.items.forEach(section => {
                                if (section.id in response.data.pending) {
                                    section.has_draft = response.data.pending[section.id];
                                }
                            });

                            this.reloadPreview();
                        })
                        .catch(error => this.flashError(error));
                },

                /**
                 * Endpoint for a section scoped action.
                 */
                actionFor(id, action) {
                    const url = this.urls[action].replace('__ID__', id);

                    return url + (url.includes('?') ? '&' : '?') + 'locale=' + encodeURIComponent(this.locale);
                },

                /**
                 * Turn a section on or off without leaving the editor.
                 */
                toggleStatus(section) {
                    const status = ! section.status;

                    this.$axios.post(this.actionFor(section.id, 'status'), { status })
                        .then(response => {
                            section.status = status;

                            section.has_draft = response.data.has_draft;

                            this.reloadPreview();
                        })
                        .catch(error => this.sectionError(error, section));
                },

                /**
                 * Copy a section and open the copy.
                 */
                duplicate(section) {
                    this.$axios.post(this.actionFor(section.id, 'duplicate'))
                        .then(response => {
                            this.items.splice(this.items.indexOf(section) + 1, 0, response.data.section);

                            this.reloadPreview();

                            this.select(response.data.section);
                        })
                        .catch(error => this.sectionError(error, section));
                },

                /**
                 * Delete a section, once confirmed.
                 */
                remove(section) {
                    this.$emitter.emit('open-confirm-modal', {
                        message: "@lang('admin::app.appearance.sections.index.delete-confirm')",

                        agree: () => {
                            this.$axios.post(this.actionFor(section.id, 'delete'), { _method: 'DELETE' })
                                .then(response => {
                                    this.items = this.items.filter(item => item.id !== section.id);

                                    if (this.activeId === section.id) {
                                        this.$refs.editDrawer.close();

                                        this.active = null;

                                        this.activeId = null;
                                    }

                                    this.$emitter.emit('add-flash', {
                                        type: 'success',
                                        message: response.data.message,
                                    });

                                    this.reloadPreview();
                                })
                                .catch(error => this.sectionError(error, section));
                        },
                    });
                },

                /**
                 * Publish every section that holds unpublished edits.
                 */
                publishAll() {
                    this.runOnAll(this.publishUrl);
                },

                /**
                 * Throw away every unpublished edit.
                 */
                discardAll() {
                    const active = this.active;

                    this.runOnAll(this.discardUrl)
                        .then(() => {
                            if (
                                this.isPanelOpen
                                && active
                            ) {
                                this.select(active);
                            }
                        });
                },

                /**
                 * Publish or discard the whole set in one request.
                 *
                 * Ordering is relative across the sections, so the server settles them together
                 * and answers with the list as it now stands — the client cannot work out what a
                 * discarded section reverts to on its own.
                 */
                runOnAll(url) {
                    if (! this.pendingCount) {
                        return Promise.resolve();
                    }

                    this.isBusy = true;

                    return this.$axios.post(url + '?locale=' + encodeURIComponent(this.locale))
                        .then(response => {
                            if (Array.isArray(response.data.sections)) {
                                this.items = response.data.sections;
                            }

                            this.reloadPreview();
                        })
                        .catch(error => this.flashError(error))
                        .finally(() => this.isBusy = false);
                },

                /**
                 * Surface a failed request to the operator.
                 */
                flashError(error) {
                    this.$emitter.emit('add-flash', {
                        type: 'error',
                        message: error.response?.data?.message ?? error.message,
                    });
                },

                /**
                 * Report a failed section action, dropping a section the server no longer
                 * has so that the list stops offering one that cannot be acted on.
                 */
                sectionError(error, sections) {
                    if (error.response?.status === 404) {
                        [sections].flat().filter(Boolean).forEach(section => this.forget(section));

                        this.reloadPreview();
                    }

                    this.flashError(error);
                },

                /**
                 * Drop a section from the list, closing its panel when it was the open one.
                 */
                forget(section) {
                    this.items = this.items.filter(item => item.id !== section.id);

                    if (this.activeId === section.id) {
                        this.activeId = null;
                    }
                },

                /**
                 * Follow the room the preview is given, so the frame keeps its device
                 * proportions as the window resizes and as the drawer opens over it.
                 */
                watchStage() {
                    const stage = this.$refs.previewStage;

                    if (! stage) {
                        return;
                    }

                    this.stageObserver = new ResizeObserver(([entry]) => {
                        this.stage = {
                            width: entry.contentRect.width,
                            height: entry.contentRect.height,
                        };
                    });

                    this.stageObserver.observe(stage);
                },

                /**
                 * Re-render the preview frame.
                 */
                reloadPreview() {
                    try {
                        this.$refs.preview?.contentWindow?.location.reload();
                    } catch (error) {
                        this.$refs.preview.src = this.previewUrl;
                    }
                },
            },
        });
    </script>
@endPushOnce
