@extends('admin::layouts.content')

@section('page_title')
    {{ __('bookingproduct::app.admin.sales.bookings.title') }}
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('themes/default/assets/css/admin-booking.css') }}">

    <style>
        .grid-container .datagrid-filters .filter-right {
            grid-template-columns: auto auto auto;
        }
        
        @media only screen and (max-width: 768px) {
            .vuecal__no-event {
                padding-top: 0rem !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('themes/default/assets/js/admin-booking.js') }}"></script>
@endpush

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('bookingproduct::app.admin.sales.bookings.title') }}</h1>
            </div>
        </div>

        <div class="page-content">

            @php
                $viewType = request()->view_type ?? "table";
            @endphp

            @if ($viewType == "table")
            
                <datagrid-plus src="{{ route('admin.sales.bookings.get') }}">
                    <template v-slot:extra-filters>
                        @include('bookingproduct::admin.sales.bookings.index.view-swither')
                    </template>
                </datagrid-plus>

            @else

                @include('bookingproduct::admin.sales.bookings.index.calendar')

            @endif
        </div>
    </div>
@stop
