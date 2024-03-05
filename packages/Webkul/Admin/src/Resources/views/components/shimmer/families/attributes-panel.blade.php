<div class="">
    <!-- Panel Header -->
    <div class="flex gap-2.5 justify-between flex-wrap mb-2.5 p-4">
        <!-- Panel Header -->
        <div class="flex flex-col gap-2">
            <div class="shimmer w-[54px] h-[17px]"></div>

            <div class="shimmer w-[177px] h-[17px]"></div>
        </div>

        <!-- Panel Content -->
        <div class="flex gap-x-1 items-center">
            <!-- Delete Group Button -->
            <div class="shimmer w-[130px] h-10 rounded-md"></div>

            <!-- Add Group Button -->
            <div class="shimmer w-[109px] h-10 rounded-md"></div>
        </div>
    </div>

    <!-- Panel Content -->
    <div class="flex [&amp;>*]:flex-1 gap-5 justify-between px-4">
        @for ($i = 0; $i < 2; $i++)
            <!-- Attributes Groups Container -->
            <div>
                <!-- Attributes Groups Header -->
                <div class="flex flex-col mb-4">
                    <div class="shimmer w-[82px] h-6 mb-1"></div>

                    <div class="shimmer w-[147px] h-[17px]"></div>
                </div>

                <!-- Draggable Attribute Groups -->
                <div class="h-[calc(100vh-285px)] pb-4 overflow-auto ltr:border-r rtl:border-l border-gray-200">
                    @for ($j = 0; $j < 3; $j++)
                        <div>
                            <!-- Group Container -->
                            <div class="flex items-center">
                                <!-- Toggle -->
                                <div class="shimmer w-4 h-4 mr-1"></div>

                                <!-- Group Name -->
                                <div class="group_node flex gap-1.5 max-w-max py-1.5 ltr:pr-1.5 rtl:pl-1.5">
                                    <div class="shimmer w-5 h-[21px]"></div>

                                    <div class="shimmer w-5 h-[21px]"></div>
                                    
                                    <div class="shimmer w-[105px] h-[21px]"></div>
                                </div>
                            </div>

                            <!-- Group Attributes -->
                            <div class="ltr:ml-11 rtl:mr-11">
                                @for ($k = 0; $k < 5; $k++)
                                    <div class="flex gap-1.5 max-w-max py-1.5 ltr:pr-1.5 rtl:pl-1.5">
                                        <div class="shimmer w-5 h-[21px]"></div>

                                        <div class="shimmer w-5 h-[21px]"></div>
                                        
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
            <div class="flex flex-col mb-4">
                <div class="shimmer w-[82px] h-6 mb-1"></div>

                <div class="shimmer w-[147px] h-[17px]"></div>
            </div>

            <!-- Draggable Unassigned Attributes -->
            <div class="h-[calc(100vh-285px)] pb-4 overflow-auto">
                @for ($i = 0; $i < 10; $i++)
                    <div class="flex gap-1.5 max-w-max py-1.5 ltr:pr-1.5 rtl:pl-1.5">
                        <div class="shimmer w-5 h-[21px]"></div>

                        <div class="shimmer w-5 h-[21px]"></div>
                        
                        <div class="shimmer w-[105px] h-[21px]"></div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>