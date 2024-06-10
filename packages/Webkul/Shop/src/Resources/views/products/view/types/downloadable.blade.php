@if ($product->type == 'downloadable')
    {!! view_render_event('bagisto.shop.products.view.downloadable.before', ['product' => $product]) !!}

    @if ($product->downloadable_samples->count())
        <div class="sample-list mb-6 mt-8">
            <label class="mb-3 flex font-medium">
                @lang('shop::app.products.view.type.downloadable.samples')
            </label>

            <ul>
                @foreach ($product->downloadable_samples as $sample)
                    <li class="mb-2">
                        <a 
                            href="{{ route('shop.downloadable.download_sample', ['type' => 'sample', 'id' => $sample->id]) }}" 
                            class="text-blue-700"
                            target="_blank"
                        >
                            {{ $sample->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($product->downloadable_links->count())
        <label class="mb-4 mt-8 flex font-medium max-sm:mb-1.5 max-sm:mt-3">
            @lang('shop::app.products.view.type.downloadable.links')
        </label>

        <div class="grid gap-4 max-sm:gap-1">
            @foreach ($product->downloadable_links as $link)
                <div class="flex select-none items-center gap-x-4">
                    <div class="flex items-center">
                        <v-field
                            type="checkbox"
                            name="links[]"
                            value="{{ $link->id }}"
                            id="{{ $link->id }}"
                            class="peer hidden"
                            rules="required"
                            label="@lang('shop::app.products.view.type.downloadable.links')"
                        >
                        </v-field>
                        
                        <label
                            class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-navyBlue peer-checked:text-navyBlue"
                            for="{{ $link->id }}"
                        ></label>
                        
                        <label
                            for="{{ $link->id }}"
                            class="cursor-pointer max-sm:text-sm ltr:ml-1 rtl:mr-1"
                        >
                            {{ $link->title . ' + ' . core()->currency($link->price) }}
                        </label>
                    </div>

                    @if (
                        $link->sample_file
                        || $link->sample_url
                    )
                        <a 
                            href="{{ route('shop.downloadable.download_sample', ['type' => 'link', 'id' => $link->id]) }}"
                            target="_blank"
                            class="text-blue-700 max-sm:text-sm"
                        >
                            @lang('shop::app.products.view.type.downloadable.sample')
                        </a>
                    @endif
                </div>
            @endforeach

            <v-error-message
                name="links[]"
                v-slot="{ message }"
            >
                <p class="text-xs italic text-red-500">
                    @{{ message }}
                </p>
            </v-error-message>
        </div>
    @endif

    {!! view_render_event('bagisto.shop.products.view.downloadable.before', ['product' => $product]) !!}
@endif
