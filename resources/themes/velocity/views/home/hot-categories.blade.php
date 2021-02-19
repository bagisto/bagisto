<div class="container-fluid hot-categories-container">
    <card-list-header heading="{{ __('velocity::app.home.hot-categories') }}">
    </card-list-header>

    <div class="row">
        @foreach ($category as $slug)
            <hot-category slug="{{ $slug }}"></hot-category>
        @endforeach
    </div>
</div>