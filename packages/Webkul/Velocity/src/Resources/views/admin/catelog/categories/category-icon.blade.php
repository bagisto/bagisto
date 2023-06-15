<div class="control-group">
    <label>{{ __('velocity::app.admin.meta-data.category-logo') }}</label>

    @if (! empty($category->category_icon_url))
        <image-wrapper
            :multiple="false"
            input-name="category_icon_path"
            images="{{ $category->category_icon_url }}"
            button-label="{{ __('admin::app.catalog.products.add-image-btn-title') }}">
        </image-wrapper>
    @else
        <image-wrapper
            :multiple="false"
            input-name="category_icon_path"
            button-label="{{ __('admin::app.catalog.products.add-image-btn-title') }}">
        </image-wrapper>
    @endif

    <span class="control-info">{{ __('admin::app.catalog.products.image-drop') }}</span>

    <span class="control-info mt-10">{{ __('admin::app.catalog.categories.image-size-logo') }}</span>
</div>