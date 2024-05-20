<div class="">
    <!-- Panel Header -->
    <div class="mb-2.5 flex flex-wrap justify-between gap-2.5 p-4">
        <!-- Panel Header -->
        <div class="flex flex-col gap-2">
            <div class="shimmer h-[17px] w-[54px]"></div>

            <div class="shimmer h-[17px] w-[177px]"></div>
        </div>

        <!-- Panel Content -->
        <div class="flex items-center gap-x-1">
            <!-- Delete Group Button -->
            <div class="shimmer h-10 w-[130px] rounded-md"></div>

            <!-- Add Group Button -->
            <div class="shimmer h-10 w-[109px] rounded-md"></div>
        </div>
    </div>

    <!-- Panel Content -->
    <div class="flex [&amp;>*]:flex-1 gap-5 justify-between px-4">
        @for ($i = 0; $i < 2; $i++)
            <!-- Attributes Groups Container -->
            <div>
                <!-- Attributes Groups Header -->
                <div class="mb-4 flex flex-col">
                    <div class="shimmer mb-1 h-6 w-[82px]"></div>

                    <div class="shimmer h-[17px] w-[147px]"></div>
                </div>

                <!-- Draggable Attribute Groups -->
                <div class="h-[calc(100vh-285px)] overflow-auto border-gray-200 pb-4 ltr:border-r rtl:border-l">
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
                </div>
            </div>
        @endfor

        <!-- Unassigned Attributes Container -->
        <div class="">
            <!-- Unassigned Attributes Header -->
            <div class="mb-4 flex flex-col">
                <div class="shimmer mb-1 h-6 w-[82px]"></div>

                <div class="shimmer h-[17px] w-[147px]"></div>
            </div>

            <!-- Draggable Unassigned Attributes -->
            <div class="h-[calc(100vh-285px)] overflow-auto pb-4">
                @for ($i = 0; $i < 10; $i++)
                    <div class="flex max-w-max gap-1.5 py-1.5 ltr:pr-1.5 rtl:pl-1.5">
                        <div class="shimmer h-[21px] w-5"></div>

                        <div class="shimmer h-[21px] w-5"></div>
                        
                        <div class="shimmer h-[21px] w-[105px]"></div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>