<div class="container-fluid popular-categories-container">
    <card-list-header heading="{{ __('velocity::app.home.popular-categories') }}">
    </card-list-header>

    <div class="row">
        @foreach ($category as $slug)
            <popular-category slug="{{ $slug }}"></popular-category>
        @endforeach
    </div>
</div>
