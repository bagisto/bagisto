<x-shop::layouts.account>
    <div class="flex justify-between">
        <h2 class="text-[26px] font-medium">
            @lang('shop::app.customers.account.title')
        </h2>

        <a
            href="{{ route('shop.customers.account.profile.edit') }}"
            class="border border-[#E9E9E9] rounded-[12px] py-[12px] px-[20px] cursor-pointer"
        >
            @lang('shop::app.customers.account.profile.edit')
        </a>
    </div>

    <div class="grid grid-cols-1 gap-y-[25px] mt-[30px]">
        <div class="grid grid-cols-[2fr_3fr] border-b-[1px] border-[#E9E9E9] w-full px-[30px] py-[12px]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.first-name')
            </p>

            <p class="text-[14px] font-medium text-[#7D7D7D]">
                {{ $customer->first_name }}
            </p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] border-b-[1px] border-[#E9E9E9] w-full px-[30px] py-[12px]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.last-name')
            </p>

            <p class="text-[14px] font-medium text-[#7D7D7D]">
                {{ $customer->last_name }}
            </p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] border-b-[1px] border-[#E9E9E9] w-full px-[30px] py-[12px]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.gender')
            </p>

            <p class="text-[14px] font-medium text-[#7D7D7D]">
                {{ $customer->gender }}
            </p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] border-b-[1px] border-[#E9E9E9] w-full px-[30px] py-[12px]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.dob')
            </p>

            <p class="text-[14px] font-medium text-[#7D7D7D]">
                {{ $customer->date_of_birth }}
            </p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] border-b-[1px] border-[#E9E9E9] w-full px-[30px] py-[12px]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.email')
            </p>

            <p class="text-[14px] font-medium text-[#7D7D7D]">
                {{ $customer->email }}
            </p>
        </div>

        <div
            class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
        >
            @lang('shop::app.customers.account.profile.delete-profile')
        </div>

        {{-- sample --}}
        <my-component></my-component>
    </div>

    {{-- sample --}}
    @pushOnce('scripts')
        <script type="text/x-template" id="test-template">
            <div>
                <p v-text="message"></p>

                <x-shop::dropdown>
                    <x-slot:toggle>
                        <button
                            class="m-0 ml-[0px] block mx-auto bg-white border-2 border-navyBlue text-navyBlue text-base w-max font-medium py-[14px] px-[29px] rounded-[18px] text-center cursor-pointer"
                        >
                            Toggle
                        </button>
                    </x-slot:toggle>

                    <x-slot:menu>
                        <x-shop::dropdown.menu.item @click="function1('Message 1')">Test 1</x-shop::dropdown.menu.item>
                        <x-shop::dropdown.menu.item @click="function2('Message 2')">Test 1</x-shop::dropdown.menu.item>
                    </x-slot:menu>
                </x-shop::dropdown>
            </div>
        </script>

        <script type="module">
            app.component("my-component", {
                template: '#test-template',

                data() {
                    return {
                        message: "This is a message from MyComponent",
                    };
                },

                methods: {
                    function1(message) {
                        alert(message);
                    },

                    function2(message) {
                        alert(message);
                    },
                }
            });
        </script>
    @endpushOnce
</x-shop::layouts.account>
