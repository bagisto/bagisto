<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>

    <title>@yield('page_title')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ bagisto_asset('css/shop.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/webkul/ui/assets/css/ui.css') }}">

    @yield('head')

    @yield('css')

</head>

<body>

    <div id="app">
        <flash-wrapper ref='flashes'></flash-wrapper>

        <div class="main-container-wrapper">

            @include('shop::layouts.header.index')

            @yield('slider')

            <div class="content-container">

                @yield('content-wrapper')

            </div>

        </div>

        @include('shop::layouts.footer.footer')

    </div>

    <script type="text/javascript">
        window.flashMessages = [];

        @if($success = session('success'))
            window.flashMessages = [{'type': 'alert-success', 'message': "{{ $success }}" }];
        @elseif($warning = session('warning'))
            window.flashMessages = [{'type': 'alert-warning', 'message': "{{ $warning }}" }];
        @elseif($error = session('error'))
            window.flashMessages = [{'type': 'alert-error', 'message': "{{ $error }}" }];
        @endif

        window.serverErrors = [];
        @if (count($errors))
            window.serverErrors = @json($errors->getMessages());
        @endif
    </script>

    <script type="text/javascript" src="{{ bagisto_asset('js/shop.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/webkul/ui/assets/js/ui.js') }}"></script>

    {{-- For JS that requires onload property --}}
    <script>
        window.onload = function () {
            //header
            var hamMenu = document.getElementById("hammenu");
            var search = document.getElementById("search");
            var sort = document.getElementById("sort");
            var filter = document.getElementById("filter");

            var searchResponsive = document.getElementsByClassName('search-responsive')[0];
            var sortLimit = document.getElementsByClassName('reponsive-sorter-limiter')[0];
            var layerFilter = document.getElementsByClassName('responsive-layred-filter')[0];
            var navResponsive = document.getElementsByClassName('responsive-nav')[0];

            search.addEventListener("click", header);
            hamMenu.addEventListener("click", header);

            //clone nav menu

        };

        // for responsive header
        function header(){
            var className = document.getElementById(this.id).className;

            if(className === 'icon search-icon' ){
                search.classList.remove("search-icon");
                search.classList.add("icon-menu-close");
                hamMenu.classList.remove("icon-menu-close");
                hamMenu.classList.add("sortable-icon");
                searchResponsive.style.display = 'block';
                navResponsive.style.display = 'none';
            }else if(className === 'icon sortable-icon'){
                hamMenu.classList.remove("sortable-icon");
                hamMenu.classList.add("icon-menu-close");
                search.classList.remove("icon-menu-close");
                search.classList.add("search-icon");
                searchResponsive.style.display = 'none';
                navResponsive.style.display = 'block';
            }else{
                search.classList.remove("icon-menu-close");
                search.classList.add("search-icon");
                hamMenu.classList.remove("icon-menu-close");
                hamMenu.classList.add("sortable-icon");
                searchResponsive.style.display = 'none';
                navResponsive.style.display = 'none';
            }
        }
    </script>

    @stack('scripts')
</body>

</html>