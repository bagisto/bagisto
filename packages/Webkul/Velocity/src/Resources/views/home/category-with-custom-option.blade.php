<div class="container-fluid category-with-custom-options">
    @php
        $category[0] = app('Webkul\Category\Repositories\CategoryRepository')->findByPath($category[0]);
        $category[1] = app('Webkul\Category\Repositories\CategoryRepository')->findByPath($category[1]);
        $category[2] = app('Webkul\Category\Repositories\CategoryRepository')->findByPath($category[2]);
        $category[3] = app('Webkul\Category\Repositories\CategoryRepository')->findByPath($category[3]);
    @endphp

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
                    <h2 class="text-uppercase">{{ $category[0]->name }}</h2>
                    <ul type="none" class="fs14">
                        @foreach ($category[0]->children as $subCategory)
                            <li>
                                <a href="{{ $subCategory->slug }}" class="unset">
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
                    <h2 class="text-uppercase">{{ $category[1]->name }}</h2>
                    <ul type="none" class="fs14">
                        @foreach ($category[1]->children as $subCategory)
                            <li>
                                <a href="{{ $subCategory->slug }}" class="unset">
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
                    <h2 class="text-uppercase">{{ $category[2]->name }}</h2>
                    <ul type="none" class="fs14">
                        @foreach ($category[2]->children as $subCategory)
                            <li>
                                <a href="{{ $subCategory->slug }}" class="unset">
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
                    <h2 class="text-uppercase">{{ $category[3]->name }}</h2>
                    <ul type="none" class="fs14">
                        @foreach ($category[3]->children as $subCategory)
                            <li>
                                <a href="{{ $subCategory->slug }}" class="unset">
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
