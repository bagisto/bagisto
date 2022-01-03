<div class="control-group">
    <label>{{ __('velocity::app.admin.general.locale_logo') }}</label>
    @if (isset($locale) && $locale->locale_image)
        <image-wrapper
            :multiple="false"
            input-name="locale_image"
            :images='"{{ Storage:: url($locale->locale_image) }}"'
            :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'">
        </image-wrapper>
    @else
        <image-wrapper
            :multiple="false"
            input-name="locale_image"
            :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'">
        </image-wrapper>
    @endif

    <span class="control-info mt-10">{{ __('velocity::app.admin.meta-data.image-locale-resolution') }}</span>
</div>