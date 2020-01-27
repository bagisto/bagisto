<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

    <head>
        <title>@yield('page_title')</title>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="content-language" content="{{ app()->getLocale() }}">

        <link rel="stylesheet" href="{{ asset('themes/velocity/assets/css/velocity.css') }}" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700&display=swap" rel="stylesheet">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="{{ asset('themes/velocity/assets/js/velocity.js') }}"></script>
        <script type="text/javascript"
            src="https://cdn.rawgit.com/igorlino/elevatezoom-plus/1.1.6/src/jquery.ez-plus.js"
        ></script>

        @if ($favicon = core()->getCurrentChannel()->favicon_url)
            <link rel="icon" sizes="16x16" href="{{ $favicon }}" />
        @else
            <link rel="icon" sizes="16x16" href="{{ asset('themes/velocity/assets/images/favicon.png') }}" />
        @endif

        @yield('head')

        @section('seo')
            <meta name="description" content="{{ core()->getCurrentChannel()->description }}"/>
        @show

        @stack('css')

        {!! view_render_event('bagisto.shop.layout.head') !!}

    </head>

    <body @if (app()->getLocale() == 'ar') class="rtl" @endif>

        {!! view_render_event('bagisto.shop.layout.body.before') !!}

        @php
            $categories = [];

            foreach (app('Webkul\Category\Repositories\CategoryRepository')->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id) as $category) {

                if ($category->slug)
                    array_push($categories, $category);
            }
        @endphp

        @include('shop::UI.particals')

        <div id="app">
            {{-- <responsive-sidebar v-html="responsiveSidebarTemplate"></responsive-sidebar> --}}

            <div class="main-container-wrapper">

                @section('body-header')
                    @include('shop::layouts.top-nav.index')
                    @include('shop::layouts.header.index', [
                        'categories' => $categories
                    ])

                    <div class="main-content-wrapper col-12 no-padding">
                        @php
                            $velocityContent = app('Webkul\Velocity\Repositories\ContentRepository')->getAllContents();
                        @endphp

                        <content-header
                            url="{{ url()->to('/') }}"
                            is-enabled="{{ sizeof($categories) }}"
                            categories="{{ json_encode($categories) }}"
                            :header-content="{{ json_encode($velocityContent) }}"
                            heading= "{{ __('velocity::app.menu-navbar.text-category') }}"
                        ></content-header>

                        <div class="">
                            <div class="row col-12 remove-padding-margin">
                                <sidebar-component
                                    main-sidebar=true
                                    id="sidebar-level-0"
                                    url="{{ url()->to('/') }}"
                                    :categories="{{ json_encode($categories) }}"
                                    category-count="{{ $velocityMetaData ? $velocityMetaData->sidebar_category_count : 10 }}"
                                    add-class="category-list-container pt10">
                                </sidebar-component>

                                <div
                                    class="col-12 no-padding content" id="home-right-bar-container">

                                    <div class="container-right row no-margin col-12 no-padding">

                                        {!! view_render_event('bagisto.shop.layout.content.before') !!}

                                        @yield('content-wrapper')

                                        {!! view_render_event('bagisto.shop.layout.content.after') !!}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @show

                <div class="container">

                    {!! view_render_event('bagisto.shop.layout.full-content.before') !!}

                        @yield('full-content-wrapper')

                    {!! view_render_event('bagisto.shop.layout.full-content.after') !!}

                </div>
            </div>
        </div>

        <!-- below footer -->
        @section('footer')
            {!! view_render_event('bagisto.shop.layout.footer.before') !!}

                @include('shop::layouts.footer.index')

            {!! view_render_event('bagisto.shop.layout.footer.after') !!}
        @show

        {!! view_render_event('bagisto.shop.layout.body.after') !!}

        <script type="text/javascript">
            (() => {
                var showAlert = (messageType, messageLabel, message) => {
                    if (messageType && message !== '') {
                        let html = `<div class="alert ${messageType} alert-dismissible" id="alert">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>${messageLabel} !</strong> ${message}.
                        </div>`;

                        document.body.innerHTML += html;

                        window.setTimeout(() => {
                            $(".alert").fadeTo(500, 0).slideUp(500, () => {
                                $(this).remove();
                            });
                        }, 5000);
                    }
                }

                let messageType = 'alert-success';
                let messageLabel = 'dsfghjkl';

                @if ($message = session('success'))
                    messageType = 'alert-success';
                    messageLabel = 'Success';
                @elseif ($message = session('warning'))
                    messageType = 'alert-warning';
                    messageLabel = 'Warning';
                @elseif ($message = session('error'))
                    messageType = 'alert-danger';
                    messageLabel = 'Error';
                @elseif ($message = session('info'))
                    messageType = 'alert-info';
                    messageLabel = 'Info';
                @endif

                if (messageType && '{{ $message }}' !== '') {
                    showAlert(messageType, messageLabel, '{{ $message }}');
                }

                window.serverErrors = [];
                @if(isset($errors))
                    @if (count($errors))
                        window.serverErrors = @json($errors->getMessages());
                    @endif
                @endif
            })()
        </script>

        <script
            type="text/javascript"
            src="{{ asset('vendor/webkul/ui/assets/js/ui.js') }}">
        </script>

        @stack('scripts')

    </body>
</html>
