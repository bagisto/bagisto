<div class="footer">
    <div class="footer-content">
        <div class="footer-list-container">
            <div class="list-container">
                <span class="list-heading">Categories</span>

                <ul class="list-group">
                    @foreach($categories as $key => $category)
                        <li>
                            <a href="{{ route('shop.categories.index', $category['slug']) }}">{{ $category['name'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {!! DbView::make(core()->getCurrentChannel())->field('footer_content')->render() !!}

            <div class="list-container">
                <span class="list-heading">{{ __('shop::app.footer.subscribe-newsletter') }}</span>
                <div class="form-container">
                    <form action="{{ route('shop.subscribe') }}" @submit.prevent="onSubmit">
                        <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                            <input type="text" class="control subscribe-field" name="email" placeholder="Email Address" required><br/>

                            <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>

                            <button class="btn btn-md btn-primary">{{ __('shop::app.subscription.subscribe') }}</button>
                        </div>
                    </form>
                </div>

                <span class="list-heading">{{ __('shop::app.footer.locale') }}</span>
                <div class="form-container">
                    <div class="control-group">
                        <select class="control locale-switcher" onchange="window.location.href = this.value">

                            @foreach (core()->getCurrentChannel()->locales as $locale)
                                <option value="?locale={{ $locale->code }}" {{ $locale->code == app()->getLocale() ? 'selected' : '' }}>{{ $locale->name }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>

                <div class="currency">
                    <span class="list-heading">{{ __('shop::app.footer.currency') }}</span>
                    <div class="form-container">
                        <div class="control-group">
                            <select class="control locale-switcher" onchange="window.location.href = this.value">

                                @foreach (core()->getCurrentChannel()->currencies as $currency)
                                    <option value="?currency={{ $currency->code }}" {{ $currency->code == core()->getCurrentCurrencyCode() ? 'selected' : '' }}>{{ $currency->code }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>