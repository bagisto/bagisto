<div class="">
    <!-- Panel Header -->
    <div class="flex gap-[10px] justify-between flex-wrap mb-[10px] p-[16px]">
        <!-- Panel Header -->
        <div class="flex flex-col gap-[8px]">
            <div class="shimmer w-[54px] h-[17px]"></div>

            <div class="shimmer w-[177px] h-[17px]"></div>
        </div>

        <!-- Panel Content -->
        <div class="flex gap-x-[4px] items-center">
            <!-- Delete Group Button -->
            <div class="shimmer w-[130px] h-[40px] rounded-[6px]"></div>

            <!-- Add Group Button -->
            <div class="shimmer w-[109px] h-[40px] rounded-[6px]"></div>
        </div>
    </div>

    <!-- Panel Content -->
    <div class="flex [&amp;>*]:flex-1 gap-[20px] justify-between px-[16px]">
        @for ($i = 0; $i < 2; $i++)
            <!-- Attributes Groups Container -->
            <div>
                <!-- Attributes Groups Header -->
                <div class="flex flex-col mb-[16px]">
                    <div class="shimmer w-[82px] h-[24px] mb-[4px]"></div>

                    <div class="shimmer w-[147px] h-[17px]"></div>
                </div>

                <!-- Draggable Attribute Groups -->
                <div class="h-[calc(100vh-285px)] pb-[16px] overflow-auto ltr:border-r-[1px] rtl:border-l-[1px] border-gray-200">
                    @for ($j = 0; $j < 3; $j++)
                        <div>
                            <!-- Group Container -->
                            <div class="flex items-center">
                                <!-- Toggle -->
                                <div class="shimmer w-[16px] h-[16px] mr-[4px]"></div>

                                <!-- Group Name -->
                                <div class="group_node flex gap-[6px] max-w-max py-[6px] ltr:pr-[6px] rtl:pl-[6px]">
                                    <div class="shimmer w-[20px] h-[21px]"></div>

                                    <div class="shimmer w-[20px] h-[21px]"></div>
                                    
                                    <div class="shimmer w-[105px] h-[21px]"></div>
                                </div>
                            </div>

                            <!-- Group Attributes -->
                            <div class="ltr:ml-[43px] rtl:mr-[43px]">
                                @for ($k = 0; $k < 5; $k++)
                                    <div class="flex gap-[6px] max-w-max py-[6px] ltr:pr-[6px] rtl:pl-[6px]">
                                        <div class="shimmer w-[20px] h-[21px]"></div>

                                        <div class="shimmer w-[20px] h-[21px]"></div>
                                        
                                        <div class="shimmer w-[105px] h-[21px]"></div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        @endfor

        <!-- Unassigned Attributes Container -->
        <div class="">
            <!-- Unassigned Attributes Header -->
            <div class="flex flex-col mb-[16px]">
                <div class="shimmer w-[82px] h-[24px] mb-[4px]"></div>

                <div class="shimmer w-[147px] h-[17px]"></div>
            </div>

            <!-- Draggable Unassigned Attributes -->
            <div class="h-[calc(100vh-285px)] pb-[16px] overflow-auto">
                @for ($i = 0; $i < 10; $i++)
                    <div class="flex gap-[6px] max-w-max py-[6px] ltr:pr-[6px] rtl:pl-[6px]">
                        <div class="shimmer w-[20px] h-[21px]"></div>

                        <div class="shimmer w-[20px] h-[21px]"></div>
                        
                        <div class="shimmer w-[105px] h-[21px]"></div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>