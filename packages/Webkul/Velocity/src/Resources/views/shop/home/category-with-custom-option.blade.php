@php
    $category[0] = app('Webkul\Category\Repositories\CategoryRepository')->findByPath($category[0]);
    $category[1] = app('Webkul\Category\Repositories\CategoryRepository')->findByPath($category[1]);
    $category[2] = app('Webkul\Category\Repositories\CategoryRepository')->findByPath($category[2]);
    $category[3] = app('Webkul\Category\Repositories\CategoryRepository')->findByPath($category[3]);
@endphp

<div class="container-fluid category-with-custom-options">
    <div class="row">
        <div class="col pr15">
            <img src="{{ asset ('/storage/' . $category['2']->image) }}" />
        </div>

        <div class="col">
            <div class="card-arrow-container">
                <div class="card-arrow card-arrow-rt"></div>
            </div>

            <div class="categories-collection">
                <div class="category-text-content">
                    <h2 class="text-uppercase">
                        <a href="{{ $category[0]->slug }}" class="remove-decoration normal-white-text">
                            {{ $category[0]->name }}
                        </a>
                    </h2>
                    <ul type="none" class="fs14">
                        @foreach ($category[0]->children as $subCategory)
                            <li>
                                <a href="{{ $category[0]->slug . '/' . $subCategory->slug }}" class="remove-decoration normal-white-text">
                                    {{ $subCategory->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col pr15">
            <img src="{{ asset ('/storage/' . $category['0']->image) }}" />
        </div>

        <div class="col">
            <div class="card-arrow-container">
                <div class="card-arrow card-arrow-bt"></div>
            </div>

            <div class="categories-collection">
                <div class="category-text-content">
                    <h2 class="text-uppercase">
                        <a href="{{ $category[1]->slug }}" class="remove-decoration normal-white-text">
                            {{ $category[1]->name }}
                        </a>
                    </h2>
                    <ul type="none" class="fs14">
                        @foreach ($category[1]->children as $subCategory)
                            <li>
                                <a href="{{ $category[1]->slug . '/' . $subCategory->slug }}" class="remove-decoration normal-white-text">
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
                        <a href="{{ $category[2]->slug }}" class="remove-decoration normal-white-text">
                            {{ $category[2]->name }}
                        </a>
                    </h2>
                    <ul type="none" class="fs14">
                        @foreach ($category[2]->children as $subCategory)
                            <li>
                                <a href="{{ $category[2]->slug . '/' . $subCategory->slug }}" class="remove-decoration normal-white-text">
                                    {{ $subCategory->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col mt15">
            <img src="{{ asset ('/storage/' . $category['3']->image) }}" />
        </div>

        <div class="col mt15 mr15">
            <div class="card-arrow-container">
                <div class="card-arrow card-arrow-lt"></div>
            </div>

            <div class="categories-collection">
                <div class="category-text-content">
                    <h2 class="text-uppercase">
                        <a href="{{ $category[3]->slug }}" class="remove-decoration normal-white-text">
                            {{ $category[3]->name }}
                        </a>
                    </h2>
                    <ul type="none" class="fs14">
                        @foreach ($category[3]->children as $subCategory)
                            <li>
                                <a href="{{ $category[3]->slug . '/' . $subCategory->slug }}" class="remove-decoration normal-white-text">
                                    {{ $subCategory->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col">
            <img src="{{ asset ('/storage/' . $category['1']->image) }}" />
        </div>

    </div>
</div>

<div class="container-fluid category-with-custom-options vc-small-screen">
    @foreach ($category as $categoryItem)
        <div class="smart-category-container">
            <div class="col-12">
                <img src="{{ asset ('/storage/' . $categoryItem->image) }}" />
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

