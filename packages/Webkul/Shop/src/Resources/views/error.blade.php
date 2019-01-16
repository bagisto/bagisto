@extends('shop::layouts.master')
@section('content-wrapper')
    <div class="error-container" style="width: 100%; display: flex; justify-content: center;">
        <div class="wrapper" style="display: flex; height: 100vh; width: 80vw;
    justify-content: space-between; align-items: center;">
            <div class="error-box">
                <div class="error-title">404</div>
                <div class="error-messgae">Page Not Found</div>
                <div class="error-description"></div>
                <a href="url()->to('/')">GO TO HOME</a>
                {{-- pass the content dynamically --}}
                Show the exception here or error message here.
            </div>

        <div class="error-graphic" style="height: 236px; width: 255px; border: 1px solid red; background-image: url('.{{ asset('images.error') }}.')">
        </div>
        </div>
    </div>
@endsection