<v-notification-item {{ $attributes }}></v-notification-item>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-notification-item-template"
    >
        <article
            class="group relative flex items-start gap-3 px-4 py-3 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
            :class="{ 'bg-blue-50/40 dark:bg-gray-950/40': ! notification.read }"
        >
            <a
                class="flex min-w-0 flex-1 items-start gap-3"
                :href="notification.url"
            >
                <!-- Icon -->
                <span
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xl"
                    aria-hidden="true"
                    :class="[notification.icon, toneClasses]"
                >
                </span>

                <!-- Content -->
                <span class="grid min-w-0 flex-1 gap-0.5">
                    <span
                        class="wrap-anywhere text-sm text-gray-800 dark:text-white"
                        :class="notification.read ? 'font-normal' : 'font-semibold'"
                        v-text="notification.title"
                    >
                    </span>

                    <span
                        class="wrap-anywhere text-xs text-gray-600 dark:text-gray-300"
                        v-if="notification.message"
                        v-text="notification.message"
                    >
                    </span>

                    <span
                        class="text-xs text-gray-500 dark:text-gray-400"
                        :title="notification.created_at"
                        v-text="notification.time_ago"
                    >
                    </span>
                </span>
            </a>

            <!-- Read Toggle -->
            <button
                type="button"
                class="mt-1 flex h-5 w-5 shrink-0 cursor-pointer items-center justify-center rounded-full"
                :title="notification.read ? labels.markUnread : labels.markRead"
                :aria-label="notification.read ? labels.markUnread : labels.markRead"
                @click="$emit('toggle-read', notification)"
            >
                <span
                    class="h-2.5 w-2.5 rounded-full transition-all"
                    aria-hidden="true"
                    :class="notification.read
                        ? 'border border-gray-300 opacity-0 group-hover:opacity-100 dark:border-gray-600'
                        : 'bg-blue-600'"
                >
                </span>
            </button>
        </article>
    </script>

    <script type="module">
        app.component('v-notification-item', {
            template: '#v-notification-item-template',

            props: ['notification'],

            emits: ['toggle-read'],

            data() {
                return {
                    labels: {
                        markRead: @json(trans('admin::app.notifications.mark-read')),

                        markUnread: @json(trans('admin::app.notifications.mark-unread')),
                    },
                };
            },

            computed: {
                /**
                 * The background and icon colour of the notification's tone.
                 */
                toneClasses() {
                    return {
                        blue: 'bg-blue-100 text-blue-600 dark:bg-blue-950 dark:text-blue-400!',
                        green: 'bg-green-100 text-green-600 dark:bg-green-950 dark:text-green-400!',
                        amber: 'bg-amber-100 text-amber-600 dark:bg-amber-950 dark:text-amber-400!',
                        red: 'bg-red-100 text-red-600 dark:bg-red-950 dark:text-red-400!',
                        purple: 'bg-purple-100 text-purple-600 dark:bg-purple-950 dark:text-purple-400!',
                        gray: 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300!',
                    }[this.notification.tone] ?? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300!';
                },
            },
        });
    </script>
@endPushOnce
