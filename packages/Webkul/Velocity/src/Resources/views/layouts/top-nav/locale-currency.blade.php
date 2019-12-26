{!! view_render_event('bagisto.shop.layout.header.locale.before') !!}

    <div class="pull-left">
        <div class="dropdown">
           <select class="btn btn-link dropdown-toggle control locale-switcher" onchange="window.location.href = this.value" @if (count(core()->getCurrentChannel()->locales) == 1) disabled="disabled" @endif>

                @foreach (core()->getCurrentChannel()->locales as $locale)
                    @if (isset($serachQuery))
                        <option value="?{{ $serachQuery }}&locale={{ $locale->code }}" {{ $locale->code == app()->getLocale() ? 'selected' : '' }}>{{ $locale->name }}</option>
                    @else
                        <option value="?locale={{ $locale->code }}" {{ $locale->code == app()->getLocale() ? 'selected' : '' }}>{{ $locale->name }}</option>
                    @endif
                @endforeach

            </select>
        </div>
    </div>

{!! view_render_event('bagisto.shop.layout.header.locale.after') !!}

{!! view_render_event('bagisto.shop.layout.header.currency-item.before') !!}

    @if (core()->getCurrentChannel()->currencies->count() > 1)
        <div class="pull-left">
            <div class="dropdown">
               <select class="btn btn-link dropdown-toggle control locale-switcher" onchange="window.location.href = this.value">
                    @foreach (core()->getCurrentChannel()->currencies as $currency)
                        @if (isset($serachQuery))
                            <option value="?{{ $serachQuery }}&currency={{ $currency->code }}" {{ $currency->code == core()->getCurrentCurrencyCode() ? 'selected' : '' }}>{{ $currency->code }}</option>
                        @else
                            <option value="?currency={{ $currency->code }}" {{ $currency->code == core()->getCurrentCurrencyCode() ? 'selected' : '' }}>{{ $currency->code }}</option>
                        @endif
                    @endforeach

                </select>
            </div>
        </div>
    @endif

{!! view_render_event('bagisto.shop.layout.header.currency-item.after') !!}
