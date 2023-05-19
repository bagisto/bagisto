{!! view_render_event('bagisto.shop.layout.footer.before') !!}

<footer class=" bg-lightOrange mt-[36px] max-sm:mt-[30px]">
    <div class="flex justify-between p-[60px] gap-x-[25px] gap-y-[30px] max-1060:flex-wrap max-1060:flex-col-reverse max-sm:px-[15px]">
        <div class="flex gap-[85px] items-start flex-wrap max-1180:gap-[25px] max-1060:justify-between">
            <ul class="grid gap-[20px] text-[14px]">
                <li>About Us</li>
                <li>Customer Service</li>
                <li>What’s New</li>
                <li>Contact Us</li>
            </ul>

            <ul class="grid gap-[20px] text-[14px]">
                <li>Order and Returns</li>
                <li>Payment Policy</li>
                <li>Shipping Policy</li>
                <li>Privacy and Cookies Policy</li>
            </ul>

            <ul class="grid gap-[20px] text-[14px]">
                <li>Order and Returns</li>
                <li>Payment Policy</li>
                <li>Shipping Policy</li>
                <li>Privacy and Cookies Policy</li>
            </ul>
        </div>

        <div class="grid gap-[10px]">
            <p class="text-[30px] italic max-w-[288px] leading-[45px] text-navyBlue">Get Ready for our Fun Newsletter!</p>
            
            <p class="text-[12px]">Subscribe to stay in touch.</p>
            
            <form class="flex items-center max-w-[445px]">
                <label for="organic-search" class="sr-only">Search</label>

                <div class="relative w-full">
                    <input type="text"
                        class="bg-[#F1EADF] w-[420px] max-w-full placeholder:text-black border-[2px] border-[#E9DECC] rounded-[12px] block px-[20px] py-[20px] text-gray-900 text-xs font-medium pr-[110px] max-1060:w-full"
                        placeholder="Email" required>

                    <button type="button"
                        class="w-max px-[26px] py-[13px] bg-white rounded-[12px] text-[12px] font-medium absolute top-[8px] right-[8px] flex items-center">
                        Submit </button>
                </div>
            </form>
        </div>
    </div>

    <div class="flex justify-between  px-[60px] py-[13px] bg-[#F1EADF]">
        <p class="text-[14px] text-[#7D7D7D]">
            © Copyright 2010 - 2023, Webkul Software (Registered in India). All rights reserved.
        </p>
    </div>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}
