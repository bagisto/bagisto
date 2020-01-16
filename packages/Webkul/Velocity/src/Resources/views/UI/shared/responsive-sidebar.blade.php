@php
    $velocityContent = app('Webkul\Velocity\Repositories\ContentRepository')->getAllContents();
@endphp

<div class="nav-container">
    <div class="wrapper">
        <div class="category-title">
            <span>
                <i class="material-icons" onclick="closeSubCategory()">chevron_left</i>
                    Mens
            </span>
        </div>

        <div class="sub-category">
            <ul type="none">
                <li>Top</li>

                <li>Bottom</li>
            </ul>
        </div>

        <div class="greeting">
            <i class="material-icons">perm_identity</i>
            <span>
                @guest('customer')
                    {{ __('velocity::app.responsive.header.greeting', ['customer' => 'Guest']) }}
                @endguest

                @auth('customer')
                    {{ __('velocity::app.responsive.header.greeting', ['customer' => auth()->guard('customer')->user()->first_name]) }}
                @endauth
                <i class="material-icons close-responsive-header" v-on:click="closeDrawer()">cancel</i>
            </span>
        </div>

        <div class="offers">
            <ul type="none">


                @foreach ($velocityContent as $index => $content)
                    <li>{{ $content->title }}</li>
                @endforeach
            </ul>
        </div>

        <div class="layered-category">
            <ul type="none">
                @foreach ($categories as $index => $category)
                    <li>
                        @if($category->category_icon_path)
                            <img
                                class="category-icon"
                                src="{{ asset('storage/' . $category->category_icon_path) }}"/>
                        @endif

                        {{ $category->name }}

                        @if(sizeof($category->children) > 0)
                            <i class="material-icons" value="{{ $category->name }}">chevron_right</i>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

@push('scripts')

<script type="text/javascript">


    function openSubCategory() {
        $('.greeting, .offers, .layered-category').hide();
        $('.sub-category, .category-title').show();
    }

    function closeSubCategory() {
        $('.greeting, .offers, .layered-category').show();
        $('.sub-category, .category-title').hide();
    }

    function closeDrawer() {
        $('.nav-container').hide();
    }
</script>

@endpush