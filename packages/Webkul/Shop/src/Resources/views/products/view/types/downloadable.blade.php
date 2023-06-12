@if ($product->type == 'downloadable')
    {!! view_render_event('bagisto.shop.products.view.downloadable.before', ['product' => $product]) !!}

    @if ($product->downloadable_samples->count())
        <div class="sample-list mb-5">
            {{-- @translations --}}
            <h3>@lang('Samples')</h3>

            <ul>
                @foreach ($product->downloadable_samples as $sample)
                    <li>
                        <a 
                            href="{{ route('shop.downloadable.download_sample', ['type' => 'sample', 'id' => $sample->id]) }}" 
                            class="text-navyBlue "
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
        <div class="grid gap-[10px]">
            @foreach ($product->downloadable_links as $link)
                <div class="select-none flex gap-x-[15px]">
                    <div class="flex">
                        <v-field
                            type="checkbox"
                            name="links[]"
                            value="{{ $link->id }}"
                            id="{{ $link->id }}"
                            class="hidden peer"
                            rules="required"
                            {{-- @translations --}}
                            label="{{ trans('shop::app.products.links') }}"
                        >
                        </v-field>
                        
                        <span class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check peer-checked:bg-navyBlue peer-checked:rounded-[4px] peer-checked:text-white"></span>
                        
                        <label
                            for="{{ $link->id }}"
                            class="ml-1 cursor-pointer"
                        >
                            {{ $link->title . ' + ' . core()->currency($link->price) }}
                        </label>

                        <a 
                            href="{{ route('shop.downloadable.download_sample', ['type' => 'link', 'id' => $link->id]) }}"
                            target="_blank"
                            class="text-navyBlue ml-2"
                        >
                            {{-- @translations --}}
                            @lang('Sample')
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {!! view_render_event('bagisto.shop.products.view.downloadable.before', ['product' => $product]) !!}
@endif
