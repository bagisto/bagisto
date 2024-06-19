<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.profile.index.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="profile" />
        @endSection
    @endif

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="mx-4 flex-auto max-md:mx-6 max-sm:mx-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <!-- Back Button -->
                <a
                    class="grid md:hidden"
                    href="{{ route('shop.customers.account.index') }}"
                >
                    <span class="icon-arrow-left rtl:icon-arrow-right text-2xl"></span>
                </a>

                <h2 class="text-2xl font-medium max-md:text-xl max-sm:text-base ltr:ml-2.5 md:ltr:ml-0 rtl:mr-2.5 md:rtl:mr-0">
                    @lang('shop::app.customers.account.profile.index.title')
                </h2>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit_button.before') !!}

            <a
                href="{{ route('shop.customers.account.profile.edit') }}"
                class="secondary-button border-zinc-200 px-5 py-3 font-normal max-md:rounded-lg max-md:py-2 max-sm:py-1.5 max-sm:text-sm"
            >
                @lang('shop::app.customers.account.profile.index.edit')
            </a>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit_button.after') !!}
        </div>

        <!-- Profile Information -->
        <div class="mt-8 grid grid-cols-1 gap-y-6 max-md:mt-5 max-sm:gap-y-4">
            {!! view_render_event('bagisto.shop.customers.account.profile.first_name.before') !!}

            <div class="grid w-full grid-cols-[2fr_3fr] border-b border-zinc-200 px-8 py-3 max-md:px-0">
                <p class="text-sm font-medium">
                    @lang('shop::app.customers.account.profile.index.first-name')
                </p>

                <p class="text-sm font-medium text-zinc-500">
                    {{ $customer->first_name }}
                </p>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.first_name.after') !!}

            {!! view_render_event('bagisto.shop.customers.account.profile.last_name.before') !!}

            <div class="grid w-full grid-cols-[2fr_3fr] border-b border-zinc-200 px-8 py-3 max-md:px-0">
                <p class="text-sm font-medium">
                    @lang('shop::app.customers.account.profile.index.last-name')
                </p>

                <p class="text-sm font-medium text-zinc-500">
                    {{ $customer->last_name }}
                </p>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.last_name.after') !!}

            {!! view_render_event('bagisto.shop.customers.account.profile.gender.before') !!}

            <div class="grid w-full grid-cols-[2fr_3fr] border-b border-zinc-200 px-8 py-3 max-md:px-0">
                <p class="text-sm font-medium">
                    @lang('shop::app.customers.account.profile.index.gender')
                </p>

                <p class="text-sm font-medium text-zinc-500">
                    {{ $customer->gender ?? '-'}}
                </p>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.gender.after') !!}

            {!! view_render_event('bagisto.shop.customers.account.profile.date_of_birth.before') !!}

            <div class="grid w-full grid-cols-[2fr_3fr] border-b border-zinc-200 px-8 py-3 max-md:px-0">
                <p class="text-sm font-medium">
                    @lang('shop::app.customers.account.profile.index.dob')
                </p>

                <p class="text-sm font-medium text-zinc-500">
                    {{ $customer->date_of_birth ?? '-' }}
                </p>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.date_of_birth.after') !!}

            {!! view_render_event('bagisto.shop.customers.account.profile.email.before') !!}

            <div class="grid w-full grid-cols-[2fr_3fr] border-b border-zinc-200 px-8 py-3 max-md:px-0">
                <p class="text-sm font-medium">
                    @lang('shop::app.customers.account.profile.index.email')
                </p>

                <p class="text-sm font-medium text-zinc-500 no-underline">
                    {{ $customer->email }}
                </p>
            </div>
            
            {!! view_render_event('bagisto.shop.customers.account.profile.email.after') !!}

            {!! view_render_event('bagisto.shop.customers.account.profile.delete.before') !!}

            <!-- Profile Delete modal -->
            <x-shop::form action="{{ route('shop.customers.account.profile.destroy') }}">
                <x-shop::modal>
                    <x-slot:toggle>
                        <div class="primary-button rounded-2xl px-11 py-3 max-md:hidden max-md:rounded-lg">
                            @lang('shop::app.customers.account.profile.index.delete-profile')
                        </div>

                        <div class="rounded-2xl py-3 text-center font-medium text-red-500 max-md:w-full max-md:max-w-full max-md:py-1.5 md:hidden">
                            @lang('shop::app.customers.account.profile.index.delete-profile')
                        </div>
                    </x-slot>

                    <x-slot:header>
                        <h2 class="text-2xl font-medium max-md:text-base">
                            @lang('shop::app.customers.account.profile.index.enter-password')
                        </h2>
                    </x-slot>

                    <x-slot:content>
                        <x-shop::form.control-group class="!mb-0">
                            <x-shop::form.control-group.control
                                type="password"
                                name="password"
                                class="px-6 py-4"
                                rules="required"
                                placeholder="Enter your password"
                            />

                            <x-shop::form.control-group.error
                                class="text-left"
                                control-name="password"
                            />
                        </x-shop::form.control-group>
                    </x-slot>

                    <!-- Modal Footer -->
                    <x-slot:footer>
                        <button
                            type="submit"
                            class="primary-button flex rounded-2xl px-11 py-3 max-md:rounded-lg max-md:px-6 max-md:text-sm"
                        >
                            @lang('shop::app.customers.account.profile.index.delete')
                        </button>
                    </x-slot>
                </x-shop::modal>
            </x-shop::form>

            {!! view_render_event('bagisto.shop.customers.account.profile.delete.after') !!}

        </div>
    </div>
</x-shop::layouts.account>
