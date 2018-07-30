<div class="header">
    <div class="header-top">
        <div class="left-content">
            <ul class="logo-container">
                {{-- @for($i=0;$i<sizeof($categories);$i++)
                    {{ $categories[$i] }}
                @endfor --}}
                <li>
                    <a href="">
                        <img class="logo" src="vendor/webkul/shop/assets/images/logo.svg" />
                    </a>
                </li>
            </ul>
            <ul class="search-container">
                <li class="search-group">
                    <input type="search" class="search-field" placeholder="Search for products">
                    <div class="q-c">
                        <span class="icon search-icon"></span>
                    </div>
                </li>
            </ul>
        </div>
        <div class="right-content">
            <ul class="search-dropdown-container">
                <li class="search-dropdown">

                </li>
            </ul>
            <ul class="account-dropdown-container">
                <li class="account-dropdown">
                    <span class="icon account-icon"></span>
                    <span class="account">Account</span>
                    <span class="icon arrow-down-icon"></span>
                </li>
            </ul>

            <ul class="product-dropdown-container">
                <li class="product-dropdown">
                    <span class="icon cart-icon"></span>
                    <span class="cart"><span class="cart-count">5</span>Products</span>
                    <span class="icon arrow-down-icon"></span>
                </li>
            </ul>
            <ul class="ham-dropdown-container">
                <li class="ham-dropdown">

                </li>
            </ul>
        </div>
        <div class="right-responsive">
            <ul class="right-wrapper">
                <li class="search-box"><span class="icon search-icon"></span></li>
                <li class="account-box"><span class="icon icon-account"></span></li>
                <li class="cart-box"><span class="icon icon-cart"></span></li>
                <li class="menu-box"><span class="icon sortable-icon"></span></li>
            </ul>

        </div>
    </div>
    <div class="header-bottom">
        @include('shop::store.header.nav-menu.navmenu')
    </div>
</div>
