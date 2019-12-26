<?php
    $brandname = app('Webkul\Velocity\Helpers\Helper');
    $topBrandsCollection = $brandname->getBrandsWithCategories();
?>

<div class="top-brands">
    <div class="top-brands-body">
            @if ($topBrandsCollection)
                <div class="top-brands-header">
                    <h2>Top Brands</h2>
                </div>

                <ul class="list-group">
                    <div class="row fs16 pb15">
                        <span>
                            @foreach($topBrandsCollection as $categoryName => $brandsCollection)
                                <div class="row fs16 pb15">
                                    <label class="mr10">{{$categoryName}} : </label>

                                    <span>
                                        @foreach ($brandsCollection as $brandIndex => $brand )
                                            {{$brand}}
                                            @if ($brandIndex + 1 !== sizeof($brandsCollection))
                                                |
                                            @endif
                                        @endforeach
                                    </span>
                                </div>
                            @endforeach
                        </span>
                    </div>
                </ul>
            @endif
    </div>
</div>