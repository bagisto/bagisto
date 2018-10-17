@extends('shop::layouts.master')
@section('content-wrapper')
    <div class="account-content">
        @include('shop::customers.account.partials.sidemenu')
        <h1>Account Index Page</h1>
    </div>
@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        var sideMenuTitle = document.getElementById("responsive-side-menu");
        var downIcon = document.getElementById("down-icon");
        var accountSideMenu = document.getElementsByClassName("account-side-menu");

        sideMenuTitle.addEventListener("click", function(){
            if(downIcon.className == 'icon icon-arrow-down right'){
                for(let i=0 ; i < accountSideMenu.length ; i++){
                    accountSideMenu[i].style.display="block";
                }

                downIcon.classList.remove("icon","icon-arrow-down","right");
                downIcon.classList.add("icon","icon-arrow-up","right");
            }else{
                for(let i=0 ; i < accountSideMenu.length ; i++){
                    accountSideMenu[i].style.display="none";
                }

                downIcon.classList.remove("icon","icon-arrow-up","right");
                downIcon.classList.add("icon","icon-arrow-down","right");
            }
        });
    });
@endpush
