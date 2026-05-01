<v-calendar></v-calendar>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-calendar-template"
    >
        <div class="mt-6">
            <div class="calendar-container rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <vue-cal
                    hide-view-selector
                    :watchRealTime="true"
                    :twelveHour="true"
                    :class="'w-full h-full bagisto-calendar'"
                    :disable-views="['years', 'year', 'month', 'day']"
                    :events="events"
                    @ready="getBookings"
                    @view-change="getBookings"
                    @event-click="toggleEvent"
                >
                    <!-- Left Arrow -->
                    <template #arrow-prev="">
                        <span class="icon-sort-left flex h-8 w-8 items-center justify-center rounded-full text-xl text-gray-500 transition-colors duration-200 hover:bg-gray-100 hover:text-gray-800 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-100"></span>
                    </template>

                    <!-- Right Arrow -->
                    <template #arrow-next="">
                        <span class="icon-sort-right flex h-8 w-8 items-center justify-center rounded-full text-xl text-gray-500 transition-colors duration-200 hover:bg-gray-100 hover:text-gray-800 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-100"></span>
                    </template>

                    <!-- No Events Content -->
                    <template #no-event>
                        <p class="hidden"></p>
                    </template>

                    <!-- Content -->
                    <template #event="{ event, view }">
                        <div
                            class="slot group relative h-full cursor-pointer overflow-hidden rounded-md border-l-[3px] text-left text-xs shadow-sm backdrop-blur-sm transition-all duration-200 hover:-translate-y-px hover:shadow-md"
                            :class="[
                                event.status === 'pending' ? 'border-amber-500 bg-amber-50/90 hover:bg-amber-100 dark:border-amber-400 dark:bg-amber-500/15 dark:hover:bg-amber-500/25' :
                                event.status === 'completed' ? 'border-emerald-500 bg-emerald-50/90 hover:bg-emerald-100 dark:border-emerald-400 dark:bg-emerald-500/15 dark:hover:bg-emerald-500/25' :
                                event.status === 'closed' ? 'border-blue-500 bg-blue-50/90 hover:bg-blue-100 dark:border-blue-400 dark:bg-blue-500/15 dark:hover:bg-blue-500/25' :
                                event.status === 'canceled' ? 'border-rose-500 bg-rose-50/90 hover:bg-rose-100 dark:border-rose-400 dark:bg-rose-500/15 dark:hover:bg-rose-500/25' :
                                'border-emerald-500 bg-emerald-50/90 hover:bg-emerald-100 dark:border-emerald-400 dark:bg-emerald-500/15 dark:hover:bg-emerald-500/25',
                                event.time_difference ? 'p-2' : 'px-2 py-1'
                            ]"
                        >
                            <div class="flex items-center gap-1 font-semibold text-gray-700 dark:text-gray-100">
                                <span
                                    class="inline-block h-1.5 w-1.5 rounded-full"
                                    :class="[
                                        event.status === 'pending' ? 'bg-amber-500' :
                                        event.status === 'completed' ? 'bg-emerald-500' :
                                        event.status === 'closed' ? 'bg-blue-500' :
                                        event.status === 'canceled' ? 'bg-rose-500' :
                                        'bg-emerald-500',
                                    ]"
                                ></span>

                                <span class="truncate">
                                    @{{ new Date(event.start).toLocaleTimeString('en-US', { hour12: true, hour: 'numeric', minute: '2-digit' }) }} - @{{ new Date(event.end).toLocaleTimeString('en-US', { hour12: true, hour: 'numeric', minute: '2-digit' }) }}
                                </span>
                            </div>

                            <div
                                v-if="event.time_difference"
                                class="mt-1 truncate text-[11px] font-medium text-gray-600 dark:text-gray-300"
                                v-text="event.full_name"
                            >
                            </div>

                            <div
                                v-if="event.time_difference && event.product_name"
                                class="truncate text-[10px] text-gray-500 dark:text-gray-400"
                                v-text="event.product_name"
                            >
                            </div>
                        </div>
                    </template>
                </vue-cal>
            </div>

            <x-admin::modal ref="myModal">
                <!-- Modal Header -->
                <x-slot:header>
                    <div class="flex w-full items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-50 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400">
                                <span class="icon-calendar text-2xl"></span>
                            </span>

                            <div>
                                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                    @lang('admin::app.sales.booking.calendar.booking-details')
                                </div>

                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    #@{{ event.order_id }}
                                </div>
                            </div>
                        </div>

                        <span
                            class="ml-auto mr-2 inline-flex w-fit shrink-0 items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold"
                            :class="[
                                event.status === 'pending' ? 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300' :
                                event.status === 'completed' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300' :
                                event.status === 'closed' ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300' :
                                event.status === 'canceled' ? 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300' :
                                'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300',
                            ]"
                        >
                            <span
                                class="inline-block h-1.5 w-1.5 rounded-full"
                                :class="[
                                    event.status === 'pending' ? 'bg-amber-500' :
                                    event.status === 'completed' ? 'bg-emerald-500' :
                                    event.status === 'closed' ? 'bg-blue-500' :
                                    event.status === 'canceled' ? 'bg-rose-500' :
                                    'bg-emerald-500',
                                ]"
                            ></span>

                            <span v-text="
                                event.status === 'completed' ? '@lang('admin::app.sales.booking.calendar.done')' :
                                event.status === 'pending' ? '@lang('admin::app.sales.booking.calendar.pending')' :
                                event.status === 'canceled' ? '@lang('admin::app.sales.booking.calendar.canceled')' :
                                event.status === 'closed' ? '@lang('admin::app.sales.booking.calendar.closed')' :
                                event.status"
                            >
                            </span>
                        </span>
                    </div>
                </x-slot>

                <!-- Modal Content -->
                <x-slot:content>
                    <div class="grid gap-5 text-sm font-normal">
                        <!-- Booking info -->
                        <div class="grid grid-cols-[120px_auto] gap-x-3 gap-y-2.5 rounded-lg border border-gray-100 bg-gray-50/60 p-4 dark:border-gray-700 dark:bg-gray-800/60">
                            <!-- Booking Date -->
                            <div
                                class="text-gray-500 dark:text-gray-400"
                                v-text="'@lang('admin::app.sales.booking.calendar.booking-date')'"
                            >
                            </div>

                            <div
                                class="font-medium text-gray-900 dark:text-gray-100"
                                v-text="new Date(event.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })"
                            >
                            </div>

                            <!-- Product -->
                            <div
                                class="text-gray-500 dark:text-gray-400"
                                v-text="'@lang('admin::app.sales.booking.calendar.product')'"
                            >
                            </div>

                            <div
                                class="font-medium text-gray-900 dark:text-gray-100"
                                v-text="event.product_name"
                            >
                            </div>

                            <!-- Booking Attributes (same order as cart) -->
                            <template
                                v-if="event.attributes?.length"
                                v-for="attribute in event.attributes"
                            >
                                <div
                                    class="text-gray-500 dark:text-gray-400"
                                    v-text="attribute.attribute_name"
                                >
                                </div>

                                <div
                                    class="font-medium text-gray-900 dark:text-gray-100"
                                    v-text="attribute.option_label"
                                >
                                </div>
                            </template>

                            <!-- Order Id -->
                            <div
                                class="text-gray-500 dark:text-gray-400"
                                v-text="'@lang('admin::app.sales.booking.calendar.order-id')'"
                            >
                            </div>

                            <div class="font-medium text-gray-900 dark:text-gray-100">
                                #@{{ event.order_id }}
                            </div>
                        </div>

                        <!-- Customer info -->
                        <div class="grid gap-3 rounded-lg border border-gray-100 bg-gray-50/60 p-4 text-gray-700 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-200">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Customer
                            </p>

                            <!-- Customer Name -->
                            <div class="flex items-center gap-2.5">
                                <span class="icon-customer-2 text-xl text-gray-400 dark:text-gray-500"></span>

                                <span class="font-medium" v-text="event.full_name"></span>
                            </div>

                            <!-- Customer Email -->
                            <div class="flex items-center gap-2.5">
                                <span class="icon-mail text-xl text-gray-400 dark:text-gray-500"></span>

                                <span v-text="event.email"></span>
                            </div>

                            <!-- Customer Phone Number -->
                            <div class="flex items-center gap-2.5">
                                <span class="icon-phone text-xl text-gray-400 dark:text-gray-500"></span>

                                <span v-text="event.contact"></span>
                            </div>

                            <!-- Customer Address -->
                            <div class="flex items-start gap-2.5">
                                <span class="icon-location mt-0.5 text-xl text-gray-400 dark:text-gray-500"></span>

                                <span class="leading-relaxed">
                                    <template v-if="event.address">
                                        @{{ event.address.split('\n').join(', ') }}
                                    </template>

                                    @{{ event.city }},
                                    @{{ event.state }},
                                    @{{ event.country }}
                                    @{{ event.postcode }}
                                </span>
                            </div>
                        </div>
                    </div>
                </x-slot>

                <!-- Modal Footer -->
                <x-slot:footer>
                    <button
                        class="primary-button h-9 p-2.5 text-base"
                        @click="redirect"
                    >
                        @lang('admin::app.sales.booking.calendar.view-details')
                    </button>
                </x-slot>
            </x-admin::modal>
        </div>
    </script>

    <script type="module">
        app.component('v-calendar', {
            template: '#v-calendar-template',

            data() {
                return {
                    events: [],

                    event: {},
                }
            },

            methods: {
                getBookings({ startDate, endDate }) {
                    const formattedStartDate = new Date(startDate).toLocaleDateString("en-US");
                    const formattedEndDate = new Date(endDate).toLocaleDateString("en-US");

                    this.$axios.get("{{ route('admin.sales.bookings.get') }}", {
                        params: {
                            view_type: 'calendar',
                            startDate: formattedStartDate,
                            endDate: formattedEndDate
                        }
                    })
                    .then(response => {
                        this.events = response.data.bookings;

                        this.events.forEach(element => {
                            const differenceInMinutes = Math.floor((new Date(element.end) - new Date(element.start)) / (1000 * 60));
                            const totalMinutes = Math.floor(differenceInMinutes / 60) * 60 + (differenceInMinutes % 60);

                            element.time_difference = totalMinutes > 60;
                        });
                    })
                    .catch(error => {});
                },

                toggleEvent(event) {
                    this.event = event;

                    this.$refs.myModal.toggle();
                },

                redirect(event) {
                    window.location.href = "{{ route('admin.sales.orders.view', 'order_id') }}/".replace('order_id', this.event.order_id)
                },
            },
        });
    </script>
@endPushOnce

@pushOnce('styles')
    <style>
        /* ============================================
           Bagisto Booking Calendar — modern theme
           ============================================ */
        .calendar-container {
            min-height: 640px;
        }

        .bagisto-calendar.vuecal {
            box-shadow: none;
            border-radius: 0.5rem;
            background: transparent;
            color: #1F2937;
        }

        .dark .bagisto-calendar.vuecal {
            color: #E5E7EB;
        }

        /* Title bar */
        .bagisto-calendar .vuecal__title-bar {
            background-color: transparent;
            border-bottom: 1px solid #E5E7EB;
            color: #1F2937;
            padding: 0.5rem 0.25rem;
            min-height: 52px;
        }

        .dark .bagisto-calendar .vuecal__title-bar {
            border-bottom-color: #374151;
            color: #F3F4F6;
        }

        .bagisto-calendar .vuecal__title-bar .vuecal__title {
            width: fit-content;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: -0.01em;
        }

        /* Heading row (weekdays) */
        .bagisto-calendar .vuecal__heading {
            height: 100%;
            border: none;
        }

        .bagisto-calendar .weekday-label {
            display: grid;
            height: fit-content;
            justify-content: left;
            text-align: left;
            padding: 10px 16px;
            row-gap: 2px;
        }

        .bagisto-calendar .weekday-label .full,
        .bagisto-calendar .weekday-label .small,
        .bagisto-calendar .weekday-label .xsmall {
            font-size: 11px;
            font-weight: 600;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .dark .bagisto-calendar .weekday-label .full,
        .dark .bagisto-calendar .weekday-label .small,
        .dark .bagisto-calendar .weekday-label .xsmall {
            color: #9CA3AF;
        }

        .bagisto-calendar .weekday-label span {
            font-size: 22px;
            font-weight: 600;
            text-transform: uppercase;
            color: #1F2937;
            letter-spacing: -0.02em;
        }

        .dark .bagisto-calendar .weekday-label span {
            color: #F3F4F6;
        }

        /* Today highlighting */
        .bagisto-calendar .vuecal__cell--today,
        .bagisto-calendar .vuecal__cell--current {
            background-color: rgba(59, 130, 246, 0.04);
        }

        .dark .bagisto-calendar .vuecal__cell--today,
        .dark .bagisto-calendar .vuecal__cell--current {
            background-color: rgba(59, 130, 246, 0.08);
        }

        .bagisto-calendar .vuecal__heading.today .weekday-label span {
            color: #2563EB;
        }

        .dark .bagisto-calendar .vuecal__heading.today .weekday-label span {
            color: #60A5FA;
        }

        /* Time column */
        .bagisto-calendar .vuecal__time-column {
            color: #9CA3AF;
            font-size: 11px;
        }

        .dark .bagisto-calendar .vuecal__time-column {
            color: #6B7280;
        }

        /* Cell borders */
        .bagisto-calendar .vuecal__cell,
        .bagisto-calendar .vuecal__cell-split {
            border-color: #F3F4F6;
        }

        .dark .bagisto-calendar .vuecal__cell,
        .dark .bagisto-calendar .vuecal__cell-split {
            border-color: #374151;
        }

        .bagisto-calendar .vuecal__time-cell-line:before {
            border-color: #F3F4F6;
        }

        .dark .bagisto-calendar .vuecal__time-cell-line:before {
            border-color: #374151;
        }

        .bagisto-calendar .vuecal__weekdays-headings {
            border-bottom: 1px solid #E5E7EB;
        }

        .dark .bagisto-calendar .vuecal__weekdays-headings {
            border-bottom-color: #374151;
        }

        /* Now line */
        .bagisto-calendar .vuecal__now-line {
            color: #EF4444;
        }

        .bagisto-calendar .vuecal__now-line:before {
            border-top-color: #EF4444;
        }

        /* Event reset (we use our own styled slot) */
        .bagisto-calendar .vuecal__event {
            background: transparent;
            box-shadow: none;
            color: inherit;
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .bagisto-calendar .vuecal__event:focus {
            outline: none;
        }

        /* Arrow buttons spacing */
        .bagisto-calendar .vuecal__arrow {
            cursor: pointer;
        }
    </style>
@endPushOnce
