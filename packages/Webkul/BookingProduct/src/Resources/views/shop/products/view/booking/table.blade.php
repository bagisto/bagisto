<div class="booking-info-row">
    <span class="icon bp-slot-icon"></span>
    <span class="title">
        {{ __('bookingproduct::app.shop.products.slot-duration') }} :

        {{ __('bookingproduct::app.shop.products.slot-duration-in-minutes', ['minutes' => 30]) }}
    </span>
</div>

@inject ('bookingSlotHelper', 'Webkul\BookingProduct\Helpers\TableSlot')

<div class="booking-info-row">
    <span class="icon bp-slot-icon"></span>
    <span class="title">
        {{ __('bookingproduct::app.shop.products.today-availability') }}
    </span>

    <span class="value">
    
        {!! $bookingSlotHelper->getTodaySlotsHtml($bookingProduct) !!}

    </span>

    <div class="toggle" @click="showDaysAvailability = ! showDaysAvailability">
        Show for all days

        <i class="icon arrow-down-icon"></i>
    </div>

    <div class="days-availability" v-show="showDaysAvailability">

        <table>
            <tbody>
                @foreach ($bookingSlotHelper->getWeekSlotDurations($bookingProduct) as $day)
                    <tr>
                        <td>{{ $day['name'] }}</td>

                        <td>
                            @if ($day['slots'] && count($day['slots']))
                                @foreach ($day['slots'] as $slot)
                                    {{ $slot['from'] . ' - ' . $slot['to'] }}</br>
                                @endforeach
                            @else
                                <span class="text-danger">{{ __('bookingproduct::app.shop.products.closed') }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@include ('bookingproduct::shop.products.view.booking.slots', [
        'bookingProduct' => $bookingProduct,
        'title' => __('bookingproduct::app.shop.products.book-a-table')
    ])

<div class="control-group" :class="[errors.has('booking[qty]') ? 'has-error' : '']">
    <label>{{ __('bookingproduct::app.shop.products.number-of-tables') }}</label>
    <input type="text" v-validate="'required'" name="booking[qty]" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.shop.products.number-of-tables') }}&quot;" style="width: 100%"/>
    <span class="control-error" v-if="errors.has('booking[qty]')">@{{ errors.first('booking[qty]') }}</span>
</div>

<div class="control-group">
    <label>{{ __('bookingproduct::app.shop.products.special-notes') }}</label>
    <textarea name="booking[note]" class="control" style="width: 100%"/>
</div>