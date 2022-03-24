@extends('admin::layouts.master')

@section('content-wrapper')
    <div class="inner-section">

        <div class="content-wrapper">

            @include ('admin::layouts.tabs')

            @yield('content')

        </div>

    </div>
@stop