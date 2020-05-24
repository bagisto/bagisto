<div class="control-group">
    <label>{{ __('velocity::app.admin.meta-data.category-logo') }}</label>

    @if (isset($category) && $category->category_icon_path)
        <image-wrapper
            :multiple="false"
            input-name="category_icon_path"
            :images='"{{ url()->to('/') . '/storage/' . $category->category_icon_path }}"'
            :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'">
        </image-wrapper>
    @else
        <image-wrapper
            :multiple="false"
            input-name="category_icon_path"
            :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'">
        </image-wrapper>
    @endif
</div>