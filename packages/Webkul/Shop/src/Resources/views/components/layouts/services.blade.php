{!! view_render_event('bagisto.shop.layout.features.before') !!}

@inject('sectionRepository', 'Webkul\Theme\Repositories\SectionRepository')

@php
    $channel = core()->getCurrentChannel();

    $sections = $sectionRepository->findAllOfType(
        'services_content',
        $channel->id,
        $channel->theme,
        app()->getLocale()
    );
@endphp

<!-- Features -->
@foreach ($sections as $section)
    @continue (empty($section->options['services']))

    <div
        class="container mt-20 max-lg:px-8 max-md:mt-10 max-md:px-4"
        v-pre
        @if ($sectionRepository->isPreviewing())
            data-section-id="{{ $section->id }}"
            data-section-name="{{ $section->name }}"
        @endif
    >
        <div class="max-md:max-y-6 flex justify-center gap-6 max-lg:flex-wrap max-md:grid max-md:grid-cols-2 max-md:gap-x-2.5 max-md:text-center">
            @foreach ($section->options['services'] as $service)
                <div class="flex items-center gap-5 bg-white max-md:grid max-md:gap-2.5 max-sm:gap-1 max-sm:px-2">
                    <span
                        class="{{ $service['service_icon'] }} flex items-center justify-center w-[60px] h-[60px] bg-white border border-black rounded-full text-4xl text-navyBlue p-2.5 max-md:m-auto max-md:w-16 max-md:h-16 max-sm:w-10 max-sm:h-10 max-sm:text-2xl"
                        role="presentation"
                    >
                    </span>

                    <div class="max-lg:grid max-lg:justify-center">
                        <!-- Service Title -->
                        <p class="font-dmserif text-base font-medium max-md:text-xl max-sm:text-sm">
                            {{ $service['title'] }}
                        </p>

                        <!-- Service Description -->
                        <p class="mt-2.5 max-w-[217px] text-sm font-medium text-zinc-500 max-md:mt-0 max-md:text-base max-sm:text-xs">
                            {{ $service['description'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endforeach

{!! view_render_event('bagisto.shop.layout.features.after') !!}