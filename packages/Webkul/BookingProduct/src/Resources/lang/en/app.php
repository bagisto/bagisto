<?php

return [
    'shop' => [
        'products' => [
            'booking-information'      => 'Booking Information',
            'location'                 => 'Location',
            'contact'                  => 'Contact',
            'email'                    => 'Email',
            'slot-duration'            => 'Slot Duration',
            'slot-duration-in-minutes' => ':minutes Minutes',
            'today-availability'       => 'Today Availability',
            'slots-for-all-days'       => 'Show for all days',
            'sunday'                   => 'Sunday',
            'monday'                   => 'Monday',
            'tuesday'                  => 'Tuesday',
            'wednesday'                => 'Wednesday',
            'thursday'                 => 'Thursday',
            'friday'                   => 'Friday',
            'saturday'                 => 'Saturday',
            'closed'                   => 'Closed',
            'book-an-appointment'      => 'Book an Appointment',
            'date'                     => 'Date',
            'slot'                     => 'Slot',
            'no-slots-available'       => 'No slots available',
            'rent-an-item'             => 'Rent an Item',
            'choose-rent-option'       => 'Choose Rent Option',
            'daily-basis'              => 'Daily Basis',
            'hourly-basis'             => 'Hourly Basis',
            'select-time-slot'         => 'Select time slot',
            'select-slot'              => 'Select Slot',
            'select-date'              => 'Select date',
            'select-rent-time'         => 'Select Rent Time',
            'from'                     => 'From',
            'to'                       => 'To',
            'book-a-table'             => 'Book a Table',
            'special-notes'            => 'Special Request/Notes',
            'event-on'                 => 'Event On',
            'book-your-ticket'         => 'Book Your Ticket',
            'per-ticket-price'         => ':price Per Ticket',
            'number-of-tickets'        => 'Number of Tickets',
            'total-tickets'            => 'Total Tickets',
            'base-price'               => 'Base Price',
            'total-price'              => 'Total Price',
            'base-price-info'          => '(This will be apply to each type of ticket for each quantity)',
        ],

        'cart' => [
            'renting_type' => 'Rent Type',
            'daily'        => 'Daily',
            'hourly'       => 'Hourly',
            'event-ticket' => 'Event Ticket',
            'event-from'   => 'Event From',
            'event-till'   => 'Event Till',
            'rent-type'    => 'Rent Type',
            'rent-from'    => 'Rent From',
            'rent-till'    => 'Rent Till',
            'booking-from' => 'Booking From',
            'booking-till' => 'Booking Till',
            'special-note' => 'Special Request/Notes',
        ],
    ],

    'admin' => [
        'catalog' => [
            'products' => [
                'edit' => [
                    'type' => [
                        'booking' => [
                            'available-every-week'  => [
                                'no'    => 'No',
                                'title' => 'Available Every Week',
                                'yes'   => 'Yes',
                            ],

                            'action'                => 'Actions',
                            'available-from'        => 'Available From',
                            'available-to'          => 'Available To',
                            'booking_type'          => 'Booking Type',
                            'break-duration'        => 'Break Time b/w Slots (Mins)',

                            'charged-per'           => [
                                'guest'  => 'Guest',
                                'title'  => 'Charged Per',
                                'table'  => 'Table',
                            ],

                            'default'  => [
                                'many'  => 'Many',
                                'one'   => 'One',
                                'title' => 'Default',
                            ],

                            'event' => [
                                'action'             => 'Actions',
                                'description'        => 'Description',
                                'name'               => 'Name',
                                'price'              => 'Price',
                                'qty'                => 'QTY',
                                'special-price'      => 'Special Price',
                                'special-price-from' => 'Special Price From',
                                'special-price-to'   => 'Special Price To',
                                'valid-from'         => 'Valid From',
                                'valid-until'        => 'Valid Until',
                            ],

                            'from-day'  => 'From Day',
                            'from'      => 'From',

                            'guest-limit'           => 'Guest Limit Per Table',
                            'guest-capacity'        => 'Guest Capacity',
                            'location'              => 'Location',

                            'modal' => [
                                'slot' => [
                                    'close'     => 'Close',
                                    'day'       => 'Day',
                                    'from'      => 'From',
                                    'from-day'  => 'From Day',
                                    'friday'    => 'Friday',
                                    'monday'    => 'Monday',
                                    'open'      => 'Open',
                                    'status'    => 'Status',
                                    'sunday'    => 'Sunday',
                                    'saturday'  => 'Saturday',
                                    'save'      => 'Save Slot',
                                    'tuesday'   => 'Tuesday',
                                    'time'      => 'Time',
                                    'to'        => 'To',
                                    'title'     => 'Add Slots',
                                    'thursday'  => 'Thursday',
                                    'wednesday' => 'Wednesday',
                                    'week'      => ':day',
                                ],

                                'ticket' => [
                                    'description'        => 'Description',
                                    'name'               => 'Name',
                                    'price'              => 'Price',
                                    'qty'                => 'Quantity',
                                    'special-price'      => 'Special Price',
                                    'special-price-from' => 'Special Price From',
                                    'special-price-to'   => 'Special Price To',
                                    'save'               => 'Save Tickets',
                                    'title'              => 'Add Tickets',
                                    'valid-from'         => 'Valid From',
                                    'valid-until'        => 'Valid Until',
                                ],
                            ],

                            'prevent-scheduling-before' => 'Prevent Scheduling Before',
                            'qty'                       => 'Qty',

                            'renting-type' => [
                                'title'        => 'Renting Type',
                                'daily'        => 'Daily Basis',
                                'daily-price'  => 'Daily Price',
                                'hourly'       => 'Hourly Basis',
                                'hourly-price' => 'Hourly Price',
                                'daily_hourly' => 'Both (Daily and Hourly Basis)',
                            ],

                            'slot-duration' => 'Slot Duration (Mins)',

                            'same-slot-for-all-days' => [
                                'no'    => 'No',
                                'title' => 'Same Slot For All days',
                                'yes'   => 'Yes',
                            ],

                            'slots' => [
                                'add'      => 'Add Slots',
                                'add-desc' => 'Add booking slot on the go.',
                                'title'    => 'Slots',
                            ],

                            'status'  => 'Status',

                            'tickets' => [
                                'add'      => 'Add Tickets',
                                'add-desc' => 'Add booking Tickets on the go.',
                                'title'    => 'Tickets',
                            ],

                            'type' => [
                                'appointment' => 'Appointment',
                                'default'     => 'Default',
                                'event'       => 'Event',
                                'many'        => 'Many',
                                'one'         => 'One',
                                'rental'      => 'Rental',
                                'title'       => 'Type',
                                'table'       => 'Table',
                            ],

                            'table' => [
                                'form' => ':from',
                                'to'   => ':to',
                            ],

                            'to-day' => 'To Day',
                            'to'     => 'To',
                            'title'  => 'Booking Information',
                        ],
                    ],
                ],
            ],
        ],

        'sales' => [
            'bookings' => [
                'index' => [
                    'title' => 'Bookings Product',
                ],

                'title' => 'Bookings Product',
            ],
        ],
    ],
];