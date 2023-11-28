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
                            'available-every-week'  => 'Available Every Week',
                            'available-from'        => 'Available From',
                            'available-to'          => 'Available To',
                            'booking_type'          => 'Booking Type',
                            'break-duration'        => 'Break Duration',
                            'charged-per'           => 'Charged Per',
                            'guest-limit-per-table' => 'Guest Limit Per Table',
                            'guest-capacity'        => 'Guest Capacity',
                            'location'              => 'Location',

                            'modal' => [
                                'slot' => [
                                    'close'     => 'Close',
                                    'day'       => 'Day',
                                    'form'      => 'From',
                                    'friday'    => 'Friday',
                                    'monday'    => 'Monday',
                                    'open'      => 'Open',
                                    'status'    => 'Status',
                                    'sunday'    => 'Sunday',
                                    'saturday'  => 'Saturday',
                                    'save'      => 'Save Slot',
                                    'tuesday'   => 'Tuesday',
                                    'to'        => 'To',
                                    'title'     => 'Add Slot',
                                    'thursday'  => 'Thursday',
                                    'wednesday' => 'Wednesday',
                                ],

                                'ticket' => [
                                    'description'   => 'Description',
                                    'name'          => 'Name',
                                    'price'         => 'Price',
                                    'qty'           => 'Quantity',
                                    'special-price' => 'Special Price',
                                    'save'          => 'Save Tickets',
                                    'title'         => 'Add Ticket',
                                    'valid-from'    => 'Valid From',
                                    'valid-until'   => 'Valid Until',
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
                                'daily-hourly' => 'Both (Daily and Hourly Basis)',
                            ],

                            'slot-duration' => 'Slot Duration',

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

                            'title' => 'Booking Information',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
