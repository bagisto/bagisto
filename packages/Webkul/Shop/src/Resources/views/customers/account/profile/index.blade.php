<x-shop::layouts.account>
    <div class="flex justify-between">
        <h2 class="text-[26px] font-medium">Profile</h2>

        <a
            href="{{ route('shop.customer.profile.edit') }}"
            class="border border-[#E9E9E9] rounded-[12px] py-[12px] px-[20px] cursor-pointer"
        >
            Edit
        </a>
    </div>

    <div class="grid grid-cols-1 gap-y-[25px] mt-[30px]">
        <div class="grid grid-cols-[2fr_3fr] border-b-[1px] border-[#E9E9E9] w-full px-[30px] py-[12px]">
            <p class="text-[14px] font-medium">First Name</p>

            <p class="text-[14px] font-medium text-[#7D7D7D]">{{ $customer->first_name }}</p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] border-b-[1px] border-[#E9E9E9] w-full px-[30px] py-[12px]">
            <p class="text-[14px] font-medium">Last Name</p>

            <p class="text-[14px] font-medium text-[#7D7D7D]">{{ $customer->last_name }}</p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] border-b-[1px] border-[#E9E9E9] w-full px-[30px] py-[12px]">
            <p class="text-[14px] font-medium">Gender</p>

            <p class="text-[14px] font-medium text-[#7D7D7D]">{{ $customer->gender }}</p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] border-b-[1px] border-[#E9E9E9] w-full px-[30px] py-[12px]">
            <p class="text-[14px] font-medium">Date of Birth</p>

            <p class="text-[14px] font-medium text-[#7D7D7D]">{{ $customer->date_of_birth }}</p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] border-b-[1px] border-[#E9E9E9] w-full px-[30px] py-[12px]">
            <p class="text-[14px] font-medium">Email</p>

            <p class="text-[14px] font-medium text-[#7D7D7D]">{{ $customer->email }}</p>
        </div>

        <div
            class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
        >
            Delete Profile
        </div>

        {{-- sample --}}
        <my-component></my-component>
    </div>

    {{-- sample --}}
    @pushOnce('scripts')
        <script type="text/x-template" id="test-template">
            <p v-text="message"></p>
        </script>

        <script type="module">
            app.component("my-component", {
                template: '#test-template',

                data() {
                    return {
                        message: "This is a message from MyComponent",
                    };
                },
            });
        </script>
    @endpushOnce
</x-shop::layouts.account>
