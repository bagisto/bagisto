<div class="footer">
    <div class="footer-content">
        <div class="footer-list-container">

            <?php
                $categories = [];

                foreach (app('Webkul\Category\Repositories\CategoryRepository')->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id) as $category){
                    if ($category->slug)
                        array_push($categories, $category);
                }
            ?>

            @if (count($categories))
                <div class="list-container">
                    <span class="list-heading">{{ __('admin::app.layouts.categories') }}</span>

                    <ul class="list-group">
                        @foreach ($categories as $key => $category)
                            <li>
                                <a href="{{ route('shop.productOrCategory.index', $category->slug) }}">{{ $category->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {!! DbView::make(core()->getCurrentChannel())->field('footer_content')->render() !!}

            <div class="list-container">
                @if(core()->getConfigData('customer.settings.newsletter.subscription'))
                    <label class="list-heading" for="subscribe-field">{{ __('shop::app.footer.subscribe-newsletter') }}</label>
                    <div class="form-container">
                        <form action="{{ route('shop.subscribe') }}">
                            <div class="control-group" :class="[errors.has('subscriber_email') ? 'has-error' : '']">
                                <input type="email" id="subscribe-field" class="control subscribe-field" name="subscriber_email" placeholder="Email Address" required><br/>

                                <button class="btn btn-md btn-primary">{{ __('shop::app.subscription.subscribe') }}</button>
                            </div>
                        </form>
                    </div>
                @endif

                <?php
                    $term = request()->input('term');

                    if (! is_null($term)) {
                        $serachQuery = 'term='.request()->input('term');
                    }
                ?>

                <label class="list-heading" for="locale-switcher">{{ __('shop::app.footer.locale') }}</label>
                <div class="form-container">
                    <div class="control-group">
                        <select class="control locale-switcher" id="locale-switcher" onchange="window.location.href = this.value" @if (count(core()->getCurrentChannel()->locales) == 1) disabled="disabled" @endif>

                            @foreach (core()->getCurrentChannel()->locales()->orderBy('name')->get() as $locale)
                                @if (isset($serachQuery))
                                    <option value="?{{ $serachQuery }}&locale={{ $locale->code }}" {{ $locale->code == app()->getLocale() ? 'selected' : '' }}>{{ $locale->name }}</option>
                                @else
                                    <option value="?locale={{ $locale->code }}" {{ $locale->code == app()->getLocale() ? 'selected' : '' }}>{{ $locale->name }}</option>
                                @endif
                            @endforeach

                        </select>
                    </div>
                </div>

                <div class="currency">
                    <label class="list-heading" for="currency-switcher">{{ __('shop::app.footer.currency') }}</label>
                    <div class="form-container">
                        <div class="control-group">
                            <select class="control locale-switcher" id="currency-switcher" onchange="window.location.href = this.value">

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
                </div>

            </div>
        </div>
    </div>
</div>
