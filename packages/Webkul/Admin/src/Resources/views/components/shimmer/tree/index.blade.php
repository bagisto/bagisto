@for ($j = 0; $j < 3; $j++)
    <div>
        <!-- Group Container -->
        <div class="flex items-center">
            <!-- Toggle -->
            <div class="shimmer mr-1 h-4 w-4"></div>

            <!-- Group Name -->
            <div class="group_node flex max-w-max gap-1.5 py-1.5 ltr:pr-1.5 rtl:pl-1.5">
                <div class="shimmer h-[21px] w-5"></div>

                <div class="shimmer h-[21px] w-5"></div>
                
                <div class="shimmer h-[21px] w-[105px]"></div>
            </div>
        </div>

        <!-- Group Attributes -->
        <div class="ltr:ml-11 rtl:mr-11">
            @for ($k = 0; $k < 5; $k++)
                <div class="flex max-w-max gap-1.5 py-1.5 ltr:pr-1.5 rtl:pl-1.5">
                    <div class="shimmer h-[21px] w-5"></div>

                    <div class="shimmer h-[21px] w-5"></div>
                    
                    <div class="shimmer h-[21px] w-[105px]"></div>
                </div>
            @endfor
        </div>
    </div>
@endfor