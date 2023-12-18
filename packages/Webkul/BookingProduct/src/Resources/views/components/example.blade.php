{{-- Time Picker --}}
<x-booking::form.control-group>
    <x-booking::form.control-group.label class="required">
        @lang('Time')
    </x-booking::form.control-group.label>

    <x-booking::form.control-group.control
        type="time"
        name="time"
        rules="required"
        label="time"
    >
    </x-booking::form.control-group.control>

    <x-booking::form.control-group.error
        control-name="time"
    >
    </x-booking::form.control-group.error>
</x-booking::form.control-group>