<div class="switch-view-container">
    @if (request('view_type'))
        <a href="{{ route('admin.sales.bookings.index') }}" class="icon-container" title="{{ __('bookingproduct::app.admin.sales.bookings.table-view') }}">
            <i class="icon table-icon"></i>
        </a>

        <a  class="icon-container active" title="{{ __('bookingproduct::app.admin.sales.bookings.calender-view') }}">
            <i class="icon calendar-white-icon"></i>
        </a>
    @else
        <a class="icon-container active" title="{{ __('bookingproduct::app.admin.sales.bookings.table-view') }}">
            <i class="icon table-white-icon"></i>
        </a>

        <a href="{{ route('admin.sales.bookings.index', ['view_type' => 'calendar']) }}" class="icon-container" title="{{ __('bookingproduct::app.admin.sales.bookings.calender-view') }}">
            <i class="icon calendar-icon"></i>
        </a>
    @endif
</div>