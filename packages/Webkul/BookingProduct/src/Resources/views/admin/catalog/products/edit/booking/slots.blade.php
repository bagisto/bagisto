@pushOnce('scripts')
    <script type="text/x-template" id="v-slots-template">
        <div
            class="grid gap-2.5 mb-3"
            :class="bookingType == 'default_slot' ? 'grid-cols-4' : 'grid-cols-3'"
        >
            @foreach (['day', 'from', 'to'] as $item)
                <div class="text-black dark:text-white">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.' . $item)
                </div>
            @endforeach

            <div
                v-if="bookingType == 'default_slot'"
                class="text-black dark:text-white"
            >
                @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.status')
            </div>
        </div>

        <div
            class="grid gap-2.5"
            :class="bookingType == 'default_slot' ? 'grid-cols-4' : 'grid-cols-3'"
            v-for="(day, key) in days"
        >
            <div
                class="text-black dark:text-white"
                v-text="day"
            >
            </div>

            <!-- Id -->
            <x-admin::form.control-group.control
                type="hidden"
                ::name="'[' + key + ']id'"
            >
            </x-admin::form.control-group.control>

            <!-- Hidden Day Value -->
            <x-admin::form.control-group.control
                type="hidden"
                ::name="'[' + key + ']day'"
                ::value="day"
            >
            </x-admin::form.control-group.control>

            <!-- Slots From -->
            <x-booking::form.control-group class="w-full mb-2.5">
                <x-booking::form.control-group.label class="hidden">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')
                </x-booking::form.control-group.label>

                <x-booking::form.control-group.control
                    type="time"
                    ::name="'[' + key + ']from'"
                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')"
                >
                </x-booking::form.control-group.control>

                <x-booking::form.control-group.error 
                    ::control-name="'[' + key + ']from'"
                >
                </x-booking::form.control-group.error>
            </x-booking::form.control-group>

            <!-- Slots To -->
            <x-booking::form.control-group class="w-full mb-2.5">
                <x-booking::form.control-group.label class="hidden">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')
                </x-booking::form.control-group.label>

                <x-booking::form.control-group.control
                    type="time"
                    ::name="'[' + key + ']to'"
                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')"
                >
                </x-booking::form.control-group.control>

                <x-booking::form.control-group.error 
                    ::control-name="'[' + key  + ']from'"
                >
                </x-booking::form.control-group.error>
            </x-booking::form.control-group>

            <!-- Status -->
            <x-admin::form.control-group
                v-if="bookingType == 'default_slot'"
                class="w-full mb-2.5"
            >
                <x-admin::form.control-group.label class="hidden">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.status')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="select"
                    ::name="'[' + key + ']status'"
                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.status')"
                >
                    <option value="1">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.open')
                    </option>

                    <option value="0">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.close')
                    </option>
                </x-admin::form.control-group.control>

                <x-admin::form.control-group.error 
                    ::control-name="'[' + key + ']status'"
                >
                </x-admin::form.control-group.error>
            </x-admin::form.control-group>
        </div>
    </script>

    <script type="module">
        app.component('v-slots', {
            template: '#v-slots-template',

            props: ['bookingType'],

            data() {
                return {
                    days: [
                        "@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.sunday')",
                        "@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.monday')",
                        "@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.tuesday')",
                        "@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.wednesday')",
                        "@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.thursday')",
                        "@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.friday')",
                        "@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.saturday')"
                    ]
                }
            },
        });
    </script>
@endpushOnce