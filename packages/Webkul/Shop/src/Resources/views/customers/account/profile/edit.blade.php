<x-shop::layouts.account>
    <h2 class="text-[26px] font-medium">Edit Profile</h2>

    <v-form
        class="rounded mt-[30px]"
        method="POST"
        action="{{ route('shop.customer.profile.store') }}"
        v-slot="{ meta, errors }"
    >
        @csrf

        <div class="flex items-center w-full gap-[30px]">
            <div class="w-[200px] h-[200px] rounded-[12px] cursor-pointer bg-[#F5F5F5]">
                <img class="" src="../images/user-placeholder.png" title="" alt="">
            </div>

            <label
                for="dropzone-file"
                class="m-0 block bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
            >
                Add Image <input id="dropzone-file" type="file" class="hidden">
            </label>
        </div>

        <x-shop::form.control class="mb-4">
            <x-slot:label>
                First Name
            </x-slot:label>

            <x-slot:control
                type="text"
                name="first_name"
                value=""
                rules="required"
                label="First Name"
                placeholder="First Name"
            >
            </x-slot:control>
        </x-shop::form.control>

        <x-shop::form.control class="mb-4">
            <x-slot:label>
                Last Name
            </x-slot:label>

            <x-slot:control
                type="text"
                name="last_name"
                value=""
                rules="required"
                label="Last Name"
                placeholder="Last Name"
            >
            </x-slot:control>
        </x-shop::form.control>

        <x-shop::form.control class="mb-4">
            <x-slot:label>
                Email
            </x-slot:label>

            <x-slot:control
                type="text"
                name="email"
                value=""
                rules="required|email"
                label="Email"
                placeholder="Email"
            >
            </x-slot:control>
        </x-shop::form.control>

        <x-shop::form.control class="mb-4">
            <x-slot:label>
                Phone
            </x-slot:label>

            <x-slot:control
                type="text"
                name="phone"
                value=""
                rules=""
                label="Phone"
                placeholder="Phone"
            >
            </x-slot:control>
        </x-shop::form.control>

        <x-shop::form.control class="mb-4">
            <x-slot:label>
                Gender
            </x-slot:label>

            <x-slot:control
                type="select"
            >
                <option value="">@lang('Select Gender')</option>
                <option value="Other">@lang('shop::app.customer.account.profile.other')</option>
                <option value="Male">@lang('shop::app.customer.account.profile.male')</option>
                <option value="Female">@lang('shop::app.customer.account.profile.female')</option>
            </x-slot:control>
        </x-shop::form.control>

        <x-shop::form.control class="mb-4">
            <x-slot:label>
                Current Password
            </x-slot:label>

            <x-slot:control
                type="password"
                name="current_password"
                value=""
                rules="required"
                label="Current Password"
                placeholder="Current Password"
            >
            </x-slot:control>
        </x-shop::form.control>

        <x-shop::form.control class="mb-4">
            <x-slot:label>
                New Password
            </x-slot:label>

            <x-slot:control
                type="password"
                name="new_password"
                value=""
                rules="required"
                label="New Password"
                placeholder="New Password"
            >
            </x-slot:control>
        </x-shop::form.control>

        <x-shop::form.control class="mb-4">
            <x-slot:label>
                Confirm Password
            </x-slot:label>

            <x-slot:control
                type="password"
                name="confirm_password"
                value=""
                rules="required|confirmed:@new_password"
                label="Confirm Password"
                placeholder="Confirm Password"
            >
            </x-slot:control>
        </x-shop::form.control>

        <x-shop::form.control class="mb-4">
            <x-slot:control type="checkbox">
                <span class="select-none text-[16] text-[#7d7d7d] max-sm:text-[12px]">
                    @lang('shop::app.customer.signup-form.subscribe-to-newsletter')
                </span>
            </x-slot:control>
        </x-shop::form.control>

        <button
            class="m-0 block bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
            type="submit"
        >
            Save
        </button>
    </v-form>
</x-shop::layouts.account>
