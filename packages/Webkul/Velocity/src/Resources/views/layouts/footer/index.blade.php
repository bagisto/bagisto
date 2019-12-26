<?php
    $categories = [];

    foreach (app('Webkul\Category\Repositories\CategoryRepository')->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id) as $category){
        if ($category->slug)
            array_push($categories, $category);
    }
?>

<div class="footer">
    <div class="footer-content">

        @include('shop::layouts.footer.newsletter-subscription')

        @include('shop::layouts.footer.footer-links')

        @if ($categories)
            @include('shop::layouts.footer.top-brands')
        @endif

        @include('shop::layouts.footer.copy-right')

    </div>
</div>


