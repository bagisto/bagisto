<!-- @author Shubham Mehrotra <shubhammehrotra.symfony@webkul.com> shubhwebkul -->

<?php
    $categories = [
        'Shoes2' => 'rango-globe',
        'Shoes3' => 'rango-globe',
        'Shoes4' => 'rango-globe',
        'Shoes5' => 'rango-globe',
        'Shoes6' => 'rango-globe',
        'Shoes7' => 'rango-globe',
        'Shoes8' => 'rango-globe',
        'Shoes18' => 'rango-globe',
        'Shoes19' => 'rango-globe',
        'Men' => 'rango-cloth',
        'Women' => 'rango-t-shirt',
        'kids' => 'rango-baby',
        'Lipstick' => 'rango-lipstick',
        'Shoes' => 'rango-shoes',
        'Shop' => 'rango-shop',
        "Jewellery" => "rango-neckless",
        "Camera" => "rango-camera",
        "Travel" => "rango-bag",
    ];

    $slicedCount = (sizeof($categories) - 9);

    $categories = array_slice($categories, $slicedCount);

    $categories['More'] = 'rango-plus';
?>

{!! view_render_event('bagisto.shop.home.categories.before') !!}

    <div class="container-fluid no-padding fs14">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 no-padding mt15">
            <!-- categories list -->
            @foreach ($categories as $categoryName => $icon)
                <div class="category">
                    <i class="{{$icon}} mr7 fs18"></i>
                    <span>{{ $categoryName }}</span>
                    <i class="angle-right-icon"></i>
                </div>

            @endforeach
        </div>

        <div class="col-sm-9 no-padding banner-icon"></div>
    </div>

{!! view_render_event('bagisto.shop.home.categories.after') !!}

<!-- @author -->
