<header>
    <div class="row col-12 remove-padding-margin velocity-divide-page">
        <a id="logo" class="left" href="{{ route('shop.home.index') }}">
            @if ($logo = core()->getCurrentChannel()->logo_url)
                <img class="logo" src="{{ $logo }}" class="img-responsive" title="" alt="" />
            @else
                <img class="logo" src="{{ asset('themes/velocity/assets/images/logo-text.png') }}" class="img-responsive" />
            @endif
        </a>

        <div class="row no-margin right">
            <div class="col-8 no-padding">
                <form
                    method="GET"
                    role="search"
                    id="search-form"
                    class="input-group"
                    action="{{ route('shop.search.index') }}">

                    <div
                        class="btn-toolbar full-width"
                        role="toolbar">

                        <div class="btn-group full-width">
                            <div class="selectdiv">
                                <select class="form-control fs13 border-right-0" name="category">
                                    <option value="">
                                        {{ __('velocity::app.header.all-categories') }}
                                    </option>

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                    <span class="select-icon rango-arrow-down"></span>
                                </select>
                            </div>

                            <div class="full-width">
                                <input
                                    required
                                    name="term"
                                    type="search"
                                    class="form-control"
                                    placeholder="{{ __('velocity::app.header.search-text') }}" />

                                <button class="btn" type="submit" id="header-search-icon">
                                    <i class="fs16 fw6 rango-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            <div class="col-4">
                {!! view_render_event('bagisto.shop.layout.header.cart-item.before') !!}

                    @include('shop::checkout.cart.mini-cart')

                {!! view_render_event('bagisto.shop.layout.header.cart-item.after') !!}
            </div>
        </div>

    </div>
</header>
