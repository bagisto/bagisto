<div class="header">
    <div class="header-top">
        <div class="left-content">
            <ul class="logo-container">
                <li>
                    <a href="">
                        <img class="logo" src="{{asset('themes/default/assets/images/Logo.svg')}}" />
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

            <ul class="product-dropdown-container">
                <li class="product-dropdown">
                    <span class="icon cart-icon"></span>
                    <span class="cart"><span class="cart-count">5</span>Products</span>
                    <span class="icon arrow-down-icon"></span>
                </li>
            </ul>
            <ul class="ham-dropdown-container">
                <li class="ham-dropdown">
                    {{-- use this section for the dropdown of the hamburger menu --}}
                </li>
            </ul>
        </div>
        <div class="right-responsive">
            <ul class="right-wrapper">
                <li class="search-box"><span class="icon search-icon" id="search"></span></li>
                <li class="account-box"><span class="icon account-icon"></span></li>
                <li class="cart-box"><span class="icon cart-icon"></span></li>
                <li class="menu-box" ><span class="icon sortable-icon" id="sortable"></span></li>
            </ul>
        </div>
    </div>

    
    
    <div class="search-suggestion">
        <div class="search-content">
            <span class="icon search-icon"></span>
            <span> Sarees India  </span>
            <span class="icon search-icon right"></span>
        </div>

        <div class="suggestion">
            <span> designer sarees   </span>
        </div>
        <div class="suggestion">
            <span> India patter sarees  </span>
        </div>
        <div class="suggestion">
            <span> Border Sarees  </span>
        </div>
    </div>

    

    <div class="header-bottom">
    @include('shop::layouts.header.nav-menu.navmenu')
    </div>
</div>

@push('scripts')  

    <script>

        window.onload = function() {
            
            var sort = document.getElementById("sortable");
            var search = document.getElementById("search");

            sort.addEventListener("click", myFunction);
            search.addEventListener("click", myFunction);

            // function for changing icon for responsive header

            function myFunction(){
               
                let className = document.getElementById(this.id).className;

                let slider  = document.getElementsByClassName("slider-block");
                let feature = document.getElementsByClassName("featured-products");
                let newUpdate = document.getElementsByClassName("news-update");

                for (let i=0 ; i < slider.length ; i++){
                    slider[i].style.display="none";
                }

                for (let i=0 ; i < feature.length ; i++){
                    feature[i].style.display="none";
                }

                for (let i=0 ; i < newUpdate.length ; i++){
                    newUpdate[i].style.display="none";
                }

                if( className == 'icon search-icon') {

                    search.classList.remove('icon', 'search-icon');
                    search.classList.add('icon', 'cross-icon');

                    sort.classList.remove('icon', 'cross-icon');
                    sort.classList.remove('icon', 'sortable-icon');
                    sort.classList.add('icon', 'sortable-icon');
                    document.getElementsByClassName("header-bottom")[0].style.display="none";
                    document.getElementsByClassName("search-suggestion")[0].style.display="block";

                }else if ( className == 'icon sortable-icon'){
                
                    sort.classList.remove('icon', 'sortable-icon');
                    sort.classList.add('icon', 'cross-icon');

                    search.classList.remove('icon', 'cross-icon');
                    search.classList.remove('icon', 'search-icon');
                    search.classList.add('icon', 'search-icon');

                    document.getElementsByClassName("header-bottom")[0].style.display="block";
                    document.getElementsByClassName("search-suggestion")[0].style.display="none";

                } else {

                    sort.classList.remove('icon', 'cross-icon');
                    search.classList.remove('icon', 'cross-icon');
                    sort.classList.remove('icon', 'sortable-icon');
                    search.classList.remove('icon', 'search-icon');
                    sort.classList.add('icon', 'sortable-icon');
                    search.classList.add('icon', 'search-icon');
                    document.getElementsByClassName("header-bottom")[0].style.display="none";
                    document.getElementsByClassName("search-suggestion")[0].style.display="none";

                    let slider  = document.getElementsByClassName("slider-block");
                    let feature = document.getElementsByClassName("featured-products");
                    let newUpdate = document.getElementsByClassName("news-update");

                    for (let i=0 ; i < slider.length ; i++){this.id
                        slider[i].style.display="block";
                    }

                    for (let i=0 ; i < feature.length ; i++){
                        feature[i].style.display="block";
                    }

                    for (let i=0 ; i < newUpdate.length ; i++){
                        newUpdate[i].style.display="block";
                    }
                }
            }
        }

    </script>


@endpush