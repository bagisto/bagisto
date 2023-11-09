{!! view_render_event('bagisto.shop.layout.features.before') !!}

<!--
    The ThemeCustomizationRepository repository is injected directly here because there is no way
    to retrieve it from the view composer, as this is an anonymous component.
-->
@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')

@php
    $customization = $themeCustomizationRepository->findOneWhere([
        'type'       => 'services_content',
        'status'     => 1,
        'channel_id' => core()->getCurrentChannel()->id,
    ]); 
@endphp

<!-- Features -->
@if ($customization)
    <div class="container mt-20 max-lg:px-[30px] max-sm:mt-[30px]">
        <div class="flex gap-[25px] justify-center max-lg:flex-wrap">
                @foreach ($customization->options['services'] as $service)
                    <div class="flex items-center gap-[20px] bg-white">
                        <span
                            class="{{$service['service_icon']}} flex items-center justify-center w-[60px] h-[60px] bg-white border border-black rounded-full text-[42px] text-navyBlue p-[10px]"
                            role="presentation"
                        ></span>

                        <div class="">
                            <!-- Service Title -->
                            <p class="text-[16px] font-medium font-dmserif">{{$service['title']}}</p>

                            <!-- Service Description -->
                            <p class="text-[14px] font-medium mt-[10px] text-[#6E6E6E] max-w-[217px]">
                                {{$service['description']}}
                            </p>
                        </div>
                    </div>
                @endforeach
        </div>
    </div>
@endif

{!! view_render_event('bagisto.shop.layout.features.after') !!}