@extends('shop::layouts.master')

@section('content-wrapper')
    <div class="account-content row no-margin velocity-divide-page">
        <div class="sidebar left">
            @include('shop::customers.account.partials.sidemenu')
        </div>

        <div class="account-layout right mt10">
            @yield('page-detail-wrapper')
        </div>
    </div>
@endsection

@push('scripts')
    {{-- push style in css section --}}
    <style>
        .accordian .accordian-header {
            padding: 10px 0;
            font-weight: 600;
        }
        .image-wrapper {
            width: 100%;
            margin-top: 10px;
            margin-bottom: 20px;
            display: inline-block;
        }
        .image-wrapper .image-item {
            float: left;
            width: 200px;
            height: 200px;
            position: relative;
            border-radius: 3px;
            margin-right: 20px;
            background: #f8f9fa;
            margin-bottom: 20px;
            display: inline-block;
            background-position: 50%;
            background-repeat: no-repeat;
            background-image: url(/vendor/webkul/ui/assets/images/placeholder-icon.svg);
        }
        .image-wrapper .image-item .remove-image {
            left: 0;
            bottom: 0;
            color: #fff;
            width: 100%;
            padding: 10px;
            cursor: pointer;
            text-align: center;
            position: absolute;
            margin-right: 20px;
            border-radius: 0 0 4px 4px;
            text-shadow: 0 1px 2px rgba(0,0,0,.24);
            background-image: linear-gradient(-180deg,rgba(0,0,0,.08),rgba(0,0,0,.24));
        }
        .image-wrapper .image-item input {
            display: none;
        }
        .image-wrapper .image-item img.preview {
            width: 100%;
            height: 100%;
        }
    <style>

    <script>
        $(document).ready(function() {
            let updateHeight = () => {
                let sidebarHeight = $('.customer-sidebar').css('height');
                let contentHeight = $('.account-layout').css('height');

                sidebarHeight = parseInt(sidebarHeight.substring(0, sidebarHeight.length - 2));
                contentHeight = parseInt(contentHeight.substring(0, contentHeight.length - 2));

                let height = sidebarHeight > contentHeight ? sidebarHeight + 30 : contentHeight;
                height = height + "px";

                $('.account-content').css('height', height);
            }

            updateHeight();

            $('.accordian-header').click(updateHeight);
        });
    </script>
@endpush
