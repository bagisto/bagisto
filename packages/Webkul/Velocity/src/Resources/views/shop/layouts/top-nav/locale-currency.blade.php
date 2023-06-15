@php
    $searchQuery = request()->input();

    if (
        $searchQuery
        && ! empty($searchQuery)
    ) {
        $searchQuery = implode('&', array_map(
            function ($v, $k) {
                if (is_array($v)) {
                    if (is_array($v)) {
                        $key = array_keys($v)[0];

                        return $k. "[$key]=" . implode('&' . $k . '[]=', $v);
                    } else {
                        return $k. '[]=' . implode('&' . $k . '[]=', $v);
                    }
                } else {
                    return $k . '=' . $v;
                }
            },
            $searchQuery,
            array_keys($searchQuery)
        ));
    } else {
        $searchQuery = false;
    }
@endphp

{!! view_render_event('bagisto.shop.layout.header.locale.before') !!}
    <div class="d-inline-block">
        <div class="dropdown">
            <div class="locale-icon">
                @if(! empty(core()->getCurrentLocale()->image_url))
                    <img src="{{ core()->getCurrentLocale()->image_url }}" alt="" width="20" height="20" />
                @else
                    <img src="{{ asset('/themes/velocity/assets/images/flags/default-locale-image.png') }}" alt="" width="20" height="20" />
                @endif
            </div>

            <select
                class="btn btn-link dropdown-toggle control locale-switcher styled-select"
                onchange="window.location.href = this.value"
                aria-label="Locale"
                @if (count(core()->getCurrentChannel()->locales) == 1)
                    disabled="disabled"
                @endif>

                @foreach (core()->getCurrentChannel()->locales()->orderBy('name')->get() as $locale)
                    @if (! empty($searchQuery))
                        <option
                            value="?{{ $searchQuery }}&locale={{ $locale->code }}"
                            {{ $locale->code == app()->getLocale() ? 'selected' : '' }}>
                            {{ $locale->name }}
                        </option>
                    @else
                        <option value="?locale={{ $locale->code }}" {{ $locale->code == app()->getLocale() ? 'selected' : '' }}>{{ $locale->name }}</option>
                    @endif
                @endforeach
            </select>

            <div class="select-icon-container">
                <span class="select-icon rango-arrow-down"></span>
            </div>
        </div>
    </div>

{!! view_render_event('bagisto.shop.layout.header.locale.after') !!}

{!! view_render_event('bagisto.shop.layout.header.currency-item.before') !!}

    @if (core()->getCurrentChannel()->currencies->count() > 1)
        <div class="d-inline-block">
            <div class="dropdown">
            <span class="currency-icon">
                {{ core()->getCurrentCurrency()->symbol }}
            </span>

               <select
                    class="btn btn-link dropdown-toggle control locale-switcher styled-select"
                    onchange="window.location.href = this.value" aria-label="Locale">
                    @foreach (core()->getCurrentChannel()->currencies as $currency)
                        @if (! empty($searchQuery))
                            <option value="?{{ $searchQuery }}&currency={{ $currency->code }}" {{ $currency->code == core()->getCurrentCurrencyCode() ? 'selected' : '' }}>{{ $currency->code }}</option>
                        @else
                            <option value="?currency={{ $currency->code }}" {{ $currency->code == core()->getCurrentCurrencyCode() ? 'selected' : '' }}>{{ $currency->code }}</option>
                        @endif
                    @endforeach

                </select>

                <div class="select-icon-container">
                    <span class="select-icon rango-arrow-down"></span>
                </div>
            </div>
        </div>
    @endif

{!! view_render_event('bagisto.shop.layout.header.currency-item.after') !!}
