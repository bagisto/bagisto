{!! view_render_event('bagisto.shop.layout.header.locale.before') !!}

    <div class="pull-left">
        <div class="dropdown">

            @php
                $localeImage = null;
            @endphp
            @foreach (core()->getCurrentChannel()->locales as $locale)
                @if ($locale->code == app()->getLocale())
                    @php
                        $localeImage = $locale->locale_image;
                    @endphp
                @endif
            @endforeach

            <div class="locale-icon">
                @if ($localeImage)
                    <img src="{{ asset('/storage/' . $localeImage) }}" onerror="this.src = '{{ asset($localeImage) }}'" />
                @elseif (app()->getLocale() == 'en')
                    <img src="{{ asset('/themes/velocity/assets/images/flags/en.png') }}" />
                @endif
            </div>

            <select
                class="btn btn-link dropdown-toggle control locale-switcher styled-select"
                onchange="window.location.href = this.value"
                @if (count(core()->getCurrentChannel()->locales) == 1)
                    disabled="disabled"
                @endif>
                
                @foreach (core()->getCurrentChannel()->locales as $locale)
                    @if (isset($serachQuery))
                        <option
                            value="?{{ $serachQuery }}&locale={{ $locale->code }}"
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
        <div class="pull-left">
            <div class="dropdown">
               <select
                    class="btn btn-link dropdown-toggle control locale-switcher styled-select"
                    onchange="window.location.href = this.value">
                    @foreach (core()->getCurrentChannel()->currencies as $currency)
                        @if (isset($serachQuery))
                            <option value="?{{ $serachQuery }}&currency={{ $currency->code }}" {{ $currency->code == core()->getCurrentCurrencyCode() ? 'selected' : '' }}>{{ $currency->code }}</option>
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
