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
    <div class="container mt-20 max-lg:px-8 max-sm:mt-8">
        <div class="flex gap-6 justify-center max-lg:flex-wrap">
            @foreach ($customization->options['services'] as $service)
                <div class="flex items-center gap-5 bg-white">
                    <span
                        class="{{$service['service_icon']}} flex items-center justify-center w-[60px] h-[60px] bg-white border border-black rounded-full text-4xl text-navyBlue p-2.5"
                        role="presentation"
                    ></span>

                    <div class="">
                        <!-- Service Title -->
                        <p class="text-base font-medium font-dmserif">{{$service['title']}}</p>

                        <!-- Service Description -->
                        <p class="text-sm font-medium mt-2.5 text-[#6E6E6E] max-w-[217px]">
                            {{$service['description']}}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

{!! view_render_event('bagisto.shop.layout.features.after') !!}