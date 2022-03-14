@if ($product->type == 'downloadable')
    {!! view_render_event('bagisto.shop.products.view.downloadable.before', ['product' => $product]) !!}

    <div class="col-12 downloadable-container">

        @if ($product->downloadable_samples->count())
            <div class="sample-list">
                <h3>{{ __('shop::app.products.samples') }}</h3>

                <ul type="none">
                    @foreach ($product->downloadable_samples as $sample)
                        <li>
                            <a class="remove-decoration" href="{{ route('shop.downloadable.download_sample', ['type' => 'sample', 'id' => $sample->id]) }}" target="_blank">
                                {{ $sample->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($product->downloadable_links->count())
            <div class=" link-list control-group" :class="[errors.has('links[]') ? 'has-error' : '']">
                <h3>{{ __('shop::app.products.links') }}</h3>

                <ul type="none" class="mt15">
                    @foreach ($product->downloadable_links as $link)
                        <li>
                            <div class="checkbox col-12 ml10">
                                <input
                                    name="links[]"
                                    type="checkbox"
                                    id="{{ $link->id }}"
                                    v-validate="'required'"
                                    value="{{ $link->id }}"
                                    data-vv-as="&quot;{{ __('shop::app.products.links') }}&quot;" />

                                <span>{{ $link->title . ' + ' . core()->currency($link->price) }}</span>

                                @if ($link->sample_file || $link->sample_url)
                                    <a
                                        target="_blank"
                                        class="remove-decoration"
                                        href="{{ route('shop.downloadable.download_sample', [
                                            'type' => 'link',
                                            'id' => $link->id
                                        ]) }}">

                                        {{ __('shop::app.products.sample') }}
                                    </a>
                            </div>

                            @endif
                        </li>
                    @endforeach
                </ul>

                <span class="control-error" v-if="errors.has('links[]')" v-text="errors.first('links[]')"></span>
            </div>
        @endif
    </div>

    {!! view_render_event('bagisto.shop.products.view.downloadable.before', ['product' => $product]) !!}
@endif