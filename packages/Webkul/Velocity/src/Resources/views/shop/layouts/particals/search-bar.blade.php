<div class="input-group">
    <form
        method="GET"
        role="search"
        id="search-form"
        action="{{ route('velocity.search.index') }}">
        <div
            class="btn-toolbar full-width search-form"
            role="toolbar">

            <searchbar-component>
                <template v-slot:image-search>
                    <image-search-component
                        status="{{core()->getConfigData('general.content.shop.image_search') == '1' ? 'true' : 'false'}}"
                        upload-src="{{ route('shop.image.search.upload') }}"
                        view-src="{{ route('shop.search.index') }}"
                        common-error="{{ __('shop::app.common.error') }}"
                        size-limit-error="{{ __('shop::app.common.image-upload-limit') }}">
                    </image-search-component>
                </template>
            </searchbar-component>

        </div>
    </form>
</div>