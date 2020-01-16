<div class="control-group">
    @if (isset($locale) && $locale->locale_image)
        <image-wrapper
            :multiple="false"
            input-name="locale_image"
            :images='"{{ url()->to('/') . '/storage/' . $locale->locale_image }}"'
            :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'">
        </image-wrapper>
    @else
        <image-wrapper
            :multiple="false"
            input-name="locale_image"
            :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'">
        </image-wrapper>
    @endif
</div>