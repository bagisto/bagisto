<div class="container-fluid hot-categories-container">
    <card-list-header heading="{{ __('velocity::app.home.hot-categories') }}">
    </card-list-header>

    <div class="row">
        @foreach ($category as $slug)
            @php
                $categoryDetails = app('Webkul\Category\Repositories\CategoryRepository')->findByPath($slug);
            @endphp

            @if ($categoryDetails)
                <div class="col hot-category-wrapper">
                    <div class="card">
                        <div class="row velocity-divide-page">
                            <div class="left">
                                <img src="{{ asset('/storage/' . $categoryDetails->image) }}" />
                            </div>

                            <div class="right">
                                <h3 class="fs20 clr-light">{{ $categoryDetails->name }}</h3>

                                <ul type="none">
                                    @foreach ($categoryDetails->children as $subCategory)
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
            @endif
        @endforeach
    </div>
</div>
