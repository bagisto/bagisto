<div class="booking-info-row">
    <span class="icon-calendar font-bold"></span>

    <span class="title">
        @lang('booking::app.shop.products.slot-duration') :

        @lang('booking::app.shop.products.slot-duration-in-minutes', ['minutes' => $bookingProduct->appointment_slot->duration])
    </span>
</div>

@inject ('bookingSlotHelper', 'Webkul\BookingProduct\Helpers\AppointmentSlot')

<div class="booking-info-row">
    <span class="icon-calendar font-bold"></span>

    <span class="title">
        @lang('booking::app.shop.products.today-availability')
    </span>

    <span class="value">
        {!! $bookingSlotHelper->getTodaySlotsHtml($bookingProduct) !!}
    </span>

    <div
        class="toggle"
        @click="showDaysAvailability = ! showDaysAvailability"
    >
        @lang('booking::app.shop.products.slots-for-all-days')

        <i :class="[! showDaysAvailability ? 'icon-arrow-down' : 'icon-arrow-up']"></i>
    </div>

    <div class="days-availability" v-show="showDaysAvailability">
        <table>
            <tbody>
                @foreach ($bookingSlotHelper->getWeekSlotDurations($bookingProduct) as $day)
                    <tr>
                        <td>{{ $day['name'] }}</td>

                        <td>
                            @if (
                                $day['slots']
                                && count($day['slots'])
                            )
                                @foreach ($day['slots'] as $slot)
                                    {{ $slot['from'] . ' - ' . $slot['to'] }}</br>
                                @endforeach
                            @else
                                <span class="text-danger">
                                    @lang('booking::app.shop.products.closed')
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@include ('booking::shop.products.view.booking.slots', ['bookingProduct' => $bookingProduct])