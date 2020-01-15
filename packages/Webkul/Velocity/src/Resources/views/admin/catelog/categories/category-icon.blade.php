<input
    type="file"
    name="category_icon_path"
    style="position: relative;top: -22px;left: 15px;" />



<image-wrapper
    :multiple="false"
    input-name="category_icon_path"
    :images='"{{ $category->image_url }}"'
    :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'">
</image-wrapper>