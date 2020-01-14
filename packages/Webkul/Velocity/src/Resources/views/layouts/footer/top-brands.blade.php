@php
    $brandname = app('Webkul\Velocity\Helpers\Helper');
    $topBrandsCollection = $brandname->getBrandsWithCategories();
@endphp

@if (! empty($topBrandsCollection))
    <div class="top-brands">
        <div class="top-brands-body">
                @if ($topBrandsCollection)
                    <div class="top-brands-header">
                        <h2>{{ __('velocity::app.shop.general.top-brands') }}</h2>
                    </div>

                    <ul class="list-group">
                        @foreach($topBrandsCollection as $categoryName => $brandsCollection)
                            <li class="row col-12">
                                <label class="mr10">{{ $categoryName }} : </label>

                                <span>
                                    @foreach ($brandsCollection as $brandIndex => $brand )
                                        {{$brand}}
                                        @if ($brandIndex + 1 !== sizeof($brandsCollection))
                                            |
                                        @endif
                                    @endforeach
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
        </div>
    </div>
@endif