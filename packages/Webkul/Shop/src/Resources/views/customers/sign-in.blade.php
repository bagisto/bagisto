<x-shop::layouts>
    <div class="container mt-20 max-1180:px-[20px]">
        <div
            class="w-full max-w-[870px] m-auto border border-[#E9E9E9] px-[90px] py-[60px] rounded-[12px] max-md:px-[30px] max-md:py-[30px]">
            <h1 class="text-[40px] font-dmserif max-sm:text-[25px]">Register User</h1>

            <p class="text-[#7D7D7D] text-[20px] mt-[15px] max-sm:text-[16px]">
                If you have an account, sign in with your email address.
            </p>

            <x-shop::form
                action="{{ route('shop.customer.session.create') }}"
                class="rounded mt-[60px] max-sm:mt-[30px]"
            >
                <x-shop::form.control-group class="mb-4">
                    <x-shop::form.control-group.label>
                        Email
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="text"
                        name="email"
                        value=""
                        rules="required|email"
                        placeholder="Email"
                    >
                    </x-shop::form.control-group.control>

                    <x-shop::form.control-group.error
                        control-name="email"
                    >
                    </x-shop::form.control-group.error>
                </x-shop::form.control-group>

                <x-shop::form.control-group class="mb-6">
                    <x-shop::form.control-group.label>
                        Password
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="password"
                        name="password"
                        value=""
                        rules="required"
                        placeholder="Password"
                    >
                    </x-shop::form.control-group.control>

                    <x-shop::form.control-group.error
                        control-name="password"
                    >
                    </x-shop::form.control-group.error>
                </x-shop::form.control-group>

                <div class="flex justify-between">
                    <div class="text-[##7D7D7D] flex items-center gap-[6px]">
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.control type="checkbox">
                                <span class="select-none  text-[16] text-[#7d7d7d] max-sm:text-[12px]">
                                    Show Password
                                </span>
                            </x-shop::form.control-group.control>
                        </x-shop::form.control-group>
                    </div>

                    <div class="block">
                        <a href="#" class="text-[16px] cursor-pointer text-black max-sm:text-[12px]">
                            <span>Forgot Password?</span>
                        </a>
                    </div>
                </div>

                <div class="flex gap-[36px] flex-wrap mt-[30px] items-center">
                    <button
                        class="m-0 ml-[0px] block mx-auto w-full bg-navyBlue text-white text-[16px] max-w-[374px] font-medium py-[16px] px-[43px] rounded-[18px] text-center"
                        type="submit">
                        Sign In
                    </button>

                    <div class="flex gap-[15px] flex-wrap">
                        <a href="" class="bg-[position:0px_-274px] bs-main-sprite w-[40px] h-[40px]"
                            aria-label="Facebook"></a>
                        <a href="" class="bg-[position:-40px_-274px] bs-main-sprite w-[40px] h-[40px]"
                            aria-label="Twitter"></a>
                        <a href="" class="bg-[position:-80px_-274px] bs-main-sprite w-[40px] h-[40px]"
                            aria-label="Pintrest"></a>
                        <a href="" class="bg-[position:-120px_-274px] bs-main-sprite w-[40px] h-[40px]"
                            aria-label="Linkdln"></a>
                        <a href="" class="bg-[position:0px_-314px] bs-main-sprite w-[40px] h-[40px]"
                            aria-label="Linkdln"></a>
                    </div>
                </div>
            </x-shop::form>

            <p class="text-[#7D7D7D] font-medium mt-[20px]">
                New customer? <a class="text-navyBlue" href="#">Create your account</a>
            </p>
        </div>

        <p class="mt-[30px] mb-[15px] text-center text-[#7d7d7d] text-xs">
            © Copyright 2010 - 2022, Webkul Software (Registered in India). All rights reserved.
        </p>
    </div>
</x-shop::layouts>
