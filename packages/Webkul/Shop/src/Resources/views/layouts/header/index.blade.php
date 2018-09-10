<div class="header">

    <div class="header-top">

        <div class="left-content">

            <ul class="logo-container">
                <li>
                    <a href="">
                        <img class="logo" src="vendor/webkul/shop/assets/images/logo.svg" />
                    </a>
                </li>
            </ul>

            <ul class="search-container">
                <li class="search-group">
                    <input type="search" class="search-field" placeholder="Search for products">
                    <div class="search-icon-wrapper">
                        <span class="icon search-icon"></span>
                    </div>
                </li>
            </ul>

        </div>

        <div class="right-content">

            {{-- Triggered on responsive mode only --}}
            <ul class="search-dropdown-container">
                <li class="search-dropdown">

                </li>
            </ul>

            <ul class="account-dropdown-container">

                <li class="account-dropdown">

                    <span class="icon account-icon"></span>

                    <div class="dropdown-toggle">

                        <div style="display: inline-block; cursor: pointer;">
                            <span class="name">Account</span>
                        </div>

                        <i class="icon arrow-down-icon active"></i>

                    </div>

                    <div class="dropdown-list bottom-right" style="display: none;">

                        <div class="dropdown-container">

                            <label>Account</label>

                            <ul>
                                <li><a href="{{ route('customer.session.index') }}">Sign In</a></li>
                                <li><a href="{{ route('customer.register.index') }}">Sign Up</a></li>
                            </ul>

                        </div>

                    </div>

                </li>

            </ul>

            <ul class="cart-dropdown">

                <li class="cart-summary">

                    <span class="icon cart-icon"></span>

                    <span class="cart"><span class="cart-count">5</span>Products</span>

                    <span class="icon arrow-down-icon"></span>

                </li>



            </ul>

            {{-- Meant for responsive views only --}}
            <ul class="ham-dropdown-container">

                <li class="ham-dropdown">
                    {{-- use this section for the dropdown of the hamburger menu --}}
                </li>

            </ul>

        </div>
        <div class="right-responsive">

            <ul class="right-wrapper">
                <li class="search-box"><span class="icon search-icon"></span></li>
                <li class="account-box"><span class="icon account-icon"></span></li>
                <li class="cart-box"><span class="icon cart-icon"></span></li>
                <li class="menu-box"><span class="icon sortable-icon"></span></li>
            </ul>

        </div>
    </div>

    <div class="header-bottom">
    @include('shop::layouts.header.nav-menu.navmenu')
    </div>

</div>

@push('scripts')
<script>
    $(window).resize(function() {
        var w = $(document).width();
        var window = {};
        window.width = $(document).width();
        window.height = $(document).height();
        if (window.width < 785) {
            $(".header").css("margin-bottom", "0");
            $(".header-top").css("margin-bottom", "0");
            $("ul.search-container").css("display", "none");
            $(".header-bottom").css("display", "none");
            $("div.right-content").css("display", "none");
            $(".right-responsive").css("display", "inherit");
        } else if (window.width > 785) {
            $(".header").css("margin-bottom", "21px");
            $(".header-top").css("margin-bottom", "16px");
            $("ul.search-container").css("display", "inherit");
            $(".header-bottom").css("display", "block");
            $("div.right-content").css("display", "inherit");
            $(".right-responsive").css("display", "none");
        }
    });

    $(document).ready(function (){
        /* Responsiveness script goes here */
        var w = $(document).width();
        var window = {};
        window.width = $(document).width();
        window.height = $(document).height();
        if (window.width < 785) {
            $(".header").css("margin-bottom", "0");
            $(".header-top").css("margin-bottom", "0");
            $("ul.search-container").css("display", "none");
            $(".header-bottom").css("display", "none");
            $("div.right-content").css("display", "none");
            $(".right-responsive").css("display", "inherit");
        }
        /* Responsiveness script ends here */
    });
</script>
@endpush