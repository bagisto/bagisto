<div class="switch-view-container">
    @if (request('view_type'))
        <a href="{{ route('admin.sales.bookings.index') }}" class="icon-container">
            <i class="icon table-icon"></i>
        </a>

        <a  class="icon-container active">
            <i class="icon calendar-white-icon"></i>
        </a>
    @else
        <a class="icon-container active">
            <i class="icon table-white-icon"></i>
        </a>

        <a href="{{ route('admin.sales.bookings.index', ['view_type' => 'calendar']) }}" class="icon-container">
            <i class="icon calendar-icon"></i>
        </a>
    @endif
</div>