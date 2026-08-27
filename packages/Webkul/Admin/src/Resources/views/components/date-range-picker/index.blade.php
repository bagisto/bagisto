@props(['startLabel', 'endLabel'])

@php
    $today = now()->format('Y-m-d');

    $presets = collect(\Webkul\Admin\Enums\Components\DateRangeOptionEnum::options('Y-m-d'))
        ->map(fn ($option) => [
            'label' => $option['label'],
            'start' => $option['from'],
            'end' => $option['to'] > $today ? $today : $option['to'],
        ])
        ->values()
        ->toArray();
@endphp

<v-date-range-picker
    start-label="{{ $startLabel }}"
    end-label="{{ $endLabel }}"
    {{ $attributes }}
></v-date-range-picker>

@pushOnce('styles')
    <style>
        .date-range-picker .flatpickr-calendar.inline {
            box-shadow: none;
            margin: 0;
            background: transparent;
        }
    </style>
@endPushOnce

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-date-range-picker-template"
    >
        <div
            class="date-range-picker relative inline-block"
            @keydown.esc="close"
        >
            <button
                type="button"
                ref="toggle"
                class="inline-flex min-h-9.75 w-full cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-sm leading-6 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                aria-haspopup="dialog"
                :aria-expanded="isOpen ? 'true' : 'false'"
                @click="toggle"
            >
                <span class="icon-calendar text-xl text-gray-400"></span>

                <span class="whitespace-nowrap">
                    @{{ selectionLabel }}
                </span>

                <span class="icon-sort-down text-2xl"></span>
            </button>

            <transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="scale-95 transform opacity-0"
                enter-to-class="scale-100 transform opacity-100"
                leave-active-class="transition duration-75 ease-in"
                leave-from-class="scale-100 transform opacity-100"
                leave-to-class="scale-95 transform opacity-0"
            >
                <div
                    class="absolute z-10 mt-1 max-w-[calc(100vw-2rem)] overflow-auto rounded-sm bg-white shadow-[0px_8px_10px_0px_rgba(0,0,0,0.20),0px_6px_30px_0px_rgba(0,0,0,0.12),0px_16px_24px_0px_rgba(0,0,0,0.14)] dark:bg-gray-900 ltr:right-0 rtl:left-0"
                    role="dialog"
                    :aria-label="`${startLabel} – ${endLabel}`"
                    v-show="isOpen"
                >
                    <div class="flex max-sm:flex-col">
                        <ul class="flex flex-col border-gray-200 py-2 dark:border-gray-800 max-sm:flex-row max-sm:flex-wrap max-sm:border-b max-sm:px-2 sm:w-40 sm:ltr:border-r sm:rtl:border-l">
                            <li v-for="preset in presets">
                                <button
                                    type="button"
                                    class="w-full cursor-pointer whitespace-nowrap rounded-sm px-4 py-2 text-sm text-gray-600 transition-all hover:bg-gray-100 focus:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950 dark:focus:bg-gray-950 ltr:text-left rtl:text-right max-sm:px-2.5"
                                    :class="{'bg-gray-100 font-semibold text-gray-800 dark:bg-gray-950 dark:text-white': isApplied(preset)}"
                                    :aria-pressed="isApplied(preset) ? 'true' : 'false'"
                                    @click="applyPreset(preset)"
                                >
                                    @{{ preset.label }}
                                </button>
                            </li>
                        </ul>

                        <div class="flex flex-col gap-3 p-3">
                            <input
                                class="hidden"
                                ref="input"
                                tabindex="-1"
                                aria-hidden="true"
                            />

                            <div class="flex gap-2 border-t pt-3 dark:border-gray-800">
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        @{{ startLabel }}
                                    </p>

                                    <p class="text-sm font-medium text-gray-800 dark:text-white">
                                        @{{ readable(draft.start) }}
                                    </p>
                                </div>

                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        @{{ endLabel }}
                                    </p>

                                    <p class="text-sm font-medium text-gray-800 dark:text-white">
                                        @{{ readable(draft.end) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </script>

    <script type="module">
        app.component('v-date-range-picker', {
            template: '#v-date-range-picker-template',

            props: {
                start: String,

                end: String,

                startLabel: String,

                endLabel: String,
            },

            emits: ['change'],

            data() {
                return {
                    presets: @json($presets),

                    draft: {
                        start: this.start,

                        end: this.end,
                    },

                    isOpen: false,

                    calendar: null,
                };
            },

            computed: {
                /**
                 * The chosen range as it reads on the trigger, collapsed to a single date when the
                 * range covers one day.
                 *
                 * @returns {string}
                 */
                selectionLabel() {
                    if (this.draft.start === this.draft.end) {
                        return this.readable(this.draft.start);
                    }

                    return `${this.readable(this.draft.start)} – ${this.readable(this.draft.end)}`;
                },
            },

            watch: {
                start(value) {
                    this.draft.start = value;
                },

                end(value) {
                    this.draft.end = value;
                },
            },

            mounted() {
                window.addEventListener('click', this.closeOnClickOutside);
            },

            beforeUnmount() {
                window.removeEventListener('click', this.closeOnClickOutside);

                this.calendar?.destroy();
            },

            methods: {
                /**
                 * A stored date read the way the admin's locale writes dates.
                 *
                 * @param {string} date
                 * @returns {string}
                 */
                readable(date) {
                    if (! date) {
                        return '';
                    }

                    const [year, month, day] = date.split('-').map(Number);

                    return new Intl.DateTimeFormat(this.locale(), {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                    }).format(new Date(year, month - 1, day));
                },

                /**
                 * The locale the admin is being viewed in.
                 *
                 * @returns {string}
                 */
                locale() {
                    const meta = document.querySelector('meta[http-equiv="content-language"]');

                    return (meta?.content ?? 'en').replace(/([a-z]{2})_([A-Z]{2})/g, '$1-$2');
                },

                /**
                 * Whether a preset covers exactly what is currently chosen.
                 *
                 * @param {object} preset
                 * @returns {boolean}
                 */
                isApplied(preset) {
                    return preset.start === this.draft.start
                        && preset.end === this.draft.end;
                },

                /**
                 * Open the panel, or close it when it is already open.
                 *
                 * @returns {void}
                 */
                toggle() {
                    this.isOpen ? this.close() : this.open();
                },

                /**
                 * Open the panel, showing what is currently chosen rather than what was last browsed.
                 *
                 * @returns {void}
                 */
                open() {
                    this.draft = {
                        start: this.start,
                        end: this.end,
                    };

                    this.isOpen = true;

                    this.$nextTick(() => {
                        this.calendar
                            ? this.calendar.setDate([this.draft.start, this.draft.end], false)
                            : this.mountCalendar();
                    });
                },

                /**
                 * Close the panel and hand the focus back to the trigger it was opened from.
                 *
                 * @returns {void}
                 */
                close() {
                    if (! this.isOpen) {
                        return;
                    }

                    this.isOpen = false;

                    this.$refs.toggle.focus();
                },

                /**
                 * Close the panel when the click that opened this landed outside it, leaving the
                 * focus where the operator put it.
                 *
                 * @param {MouseEvent} event
                 * @returns {void}
                 */
                closeOnClickOutside(event) {
                    if (! this.$el.contains(event.target)) {
                        this.isOpen = false;
                    }
                },

                /**
                 * Build the inline calendar, which only exists once the panel has been opened so a
                 * page carrying several filters pays for it when it is asked for.
                 *
                 * @returns {void}
                 */
                mountCalendar() {
                    this.calendar = new Flatpickr(this.$refs.input, {
                        inline: true,
                        mode: 'range',
                        dateFormat: 'Y-m-d',
                        maxDate: 'today',
                        showMonths: window.innerWidth >= 640 ? 2 : 1,
                        defaultDate: [this.draft.start, this.draft.end],
                        onChange: this.onRangeChange,
                    });
                },

                /**
                 * Hold a range while it is being picked, and settle on it once both ends are known,
                 * so browsing the calendar does not reload what the page is showing.
                 *
                 * @param {Date[]} dates
                 * @returns {void}
                 */
                onRangeChange(dates) {
                    if (dates.length < 2) {
                        return;
                    }

                    this.apply(this.stored(dates[0]), this.stored(dates[1]));
                },

                /**
                 * Settle on a preset, moving the calendar onto it so reopening the panel shows
                 * where the chosen range sits.
                 *
                 * @param {object} preset
                 * @returns {void}
                 */
                applyPreset(preset) {
                    this.calendar?.setDate([preset.start, preset.end], false);

                    this.apply(preset.start, preset.end);
                },

                /**
                 * Settle on a range, closing the panel and handing it to the page as one change.
                 *
                 * @param {string} start
                 * @param {string} end
                 * @returns {void}
                 */
                apply(start, end) {
                    this.draft = { start, end };

                    this.close();

                    this.$emit('change', { start, end });
                },

                /**
                 * A picked date as it is stored and sent, read off the calendar's own local date so
                 * it cannot slide a day through a timezone conversion.
                 *
                 * @param {Date} date
                 * @returns {string}
                 */
                stored(date) {
                    const month = String(date.getMonth() + 1).padStart(2, '0');

                    const day = String(date.getDate()).padStart(2, '0');

                    return `${date.getFullYear()}-${month}-${day}`;
                },
            },
        });
    </script>
@endPushOnce
