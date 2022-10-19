@php
    $categories = null;
    $categoryRepository = app('Webkul\Category\Repositories\CategoryRepository');

    $keyIndex = array_key_first($category);
    $categoryList = ! is_numeric($keyIndex) ? array_values($category) : [];

    foreach($category as $index => $categoryDetail) {
        $categorySlug = $index;
        if (empty($categoryList)) {
            $categorySlug = $categoryDetail;
        }

        $categories[] = $categoryRepository->findByPath($categorySlug);
    }
@endphp

@if (! empty($categories))
    <div class="container-fluid category-with-custom-options">
        <div class="row">
            <div class="col pr15">
                @if (isset($categoryList[2]))
                    <a href="{{ $categoryList[2] }}" target="_blank">
                        <img data-src="{{ $categories[2]->image_url }}" class="lazyload" alt="" />
                    </a>
                @else
                    <img data-src="{{ $categories[2]->image_url }}" class="lazyload" alt="" />
                @endif
            </div>

            <div class="col">
                <div class="card-arrow-container">
                    <div class="card-arrow card-arrow-rt"></div>
                </div>

                <div class="categories-collection">
                    <div class="category-text-content">
                        <h2 class="text-uppercase">
                            <a href="{{ $categories[0]->slug }}" class="remove-decoration normal-white-text">
                                {{ $categories[0]->name }}
                            </a>
                        </h2>
                        <ul type="none" class="fs14">
                            @foreach ($categories[0]->children as $subCategory)
                                <li>
                                    <a href="{{ $categories[0]->slug . '/' . $subCategory->slug }}" class="remove-decoration normal-white-text">
                                        {{ $subCategory->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col pr15">
                @if (isset($categoryList[0]))
                    <a href="{{ $categoryList[0] }}" target="_blank">
                        <img data-src="{{ $categories[0]->image_url }}" class="lazyload" alt=""/>
                    </a>
                @else
                    <img data-src="{{ $categories[0]->image_url }}" class="lazyload" alt=""/>
                @endif
            </div>

            <div class="col">
                <div class="card-arrow-container">
                    <div class="card-arrow card-arrow-bt"></div>
                </div>

                <div class="categories-collection">
                    <div class="category-text-content">
                        <h2 class="text-uppercase">
                            <a href="{{ $categories[1]->slug }}" class="remove-decoration normal-white-text">
                                {{ $categories[1]->name }}
                            </a>
                        </h2>
                        <ul type="none" class="fs14">
                            @foreach ($categories[1]->children as $subCategory)
                                <li>
                                    <a href="{{ $categories[1]->slug . '/' . $subCategory->slug }}" class="remove-decoration normal-white-text">
                                        {{ $subCategory->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col mr15">
                <div class="card-arrow-container">
                    <div class="card-arrow card-arrow-tp"></div>
                </div>

                <div class="categories-collection">
                    <div class="category-text-content">
                        <h2 class="text-uppercase">
                            <a href="{{ $categories[2]->slug }}" class="remove-decoration normal-white-text">
                                {{ $categories[2]->name }}
                            </a>
                        </h2>
                        <ul type="none" class="fs14">
                            @foreach ($categories[2]->children as $subCategory)
                                <li>
                                    <a href="{{ $categories[2]->slug . '/' . $subCategory->slug }}" class="remove-decoration normal-white-text">
                                        {{ $subCategory->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col mt15">
                @if (isset($categoryList[3]))
                    <a href="{{ $categoryList[3] }}" target="_blank">
                        <img data-src="{{ $categories[3]->image_url }}" class="lazyload" alt=""/>
                    </a>
                @else
                    <img data-src="{{ $categories[3]->image_url }}" class="lazyload" alt=""/>
                @endif
            </div>

            <div class="col mt15 mr15">
                <div class="card-arrow-container">
                    <div class="card-arrow card-arrow-lt"></div>
                </div>

                <div class="categories-collection">
                    <div class="category-text-content">
                        <h2 class="text-uppercase">
                            <a href="{{ $categories[3]->slug }}" class="remove-decoration normal-white-text">
                                {{ $categories[3]->name }}
                            </a>
                        </h2>
                        <ul type="none" class="fs14">
                            @foreach ($categories[3]->children as $subCategory)
                                <li>
                                    <a href="{{ $categories[3]->slug . '/' . $subCategory->slug }}" class="remove-decoration normal-white-text">
                                        {{ $subCategory->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col">
                @if (isset($categoryList[1]))
                    <a href="{{ $categoryList[1] }}" target="_blank">
                        <img data-src="{{ $categories[1]->image_url }}" class="lazyload" alt=""/>
                    </a>
                @else
                    <img data-src="{{ $categories[1]->image_url }}" class="lazyload" alt=""/>
                @endif
            </div>

        </div>
    </div>

    <div class="container-fluid category-with-custom-options vc-small-screen">
        @foreach ($categories as $key => $categoryItem)
            <div class="smart-category-container">
                <div class="col-12">
                    @if (isset($categoryList[$key]))
                        <a href="{{ $categoryList[$key] }}" target="_blank">
                            <img data-src="{{ $categoryItem->image_url }}" class="lazyload" alt="" />
                        </a>
                    @else
                        <img data-src="{{ $categoryItem->image_url }}" class="lazyload" alt="" />
                    @endif
                </div>

                <div class="col-12">
                    <div class="card-arrow-container">
                        <div class="card-arrow card-arrow-tp"></div>
                    </div>

                    <div class="categories-collection">
                        <div class="category-text-content">
                            <h2 class="text-uppercase">
                                <a href="{{ $categoryItem->slug }}" class="remove-decoration normal-white-text">
                                    {{ $categoryItem->name }}
                                </a>
                            </h2>
                            <ul type="none" class="fs14">
                                @foreach ($categoryItem->children as $subCategory)
                                    <li>
                                        <a href="{{ $categoryItem->slug . '/' . $subCategory->slug }}" class="remove-decoration normal-white-text">
                                            {{ $subCategory->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
