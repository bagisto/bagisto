<?php

return [
    'shop' => [
        'products' => [
            'base-price-info'          => '(This will be apply to each type of ticket for each quantity)',
            'base-price'               => 'Base Price',
            'book-a-table'             => 'Book a Table',
            'book-an-appointment'      => 'Book an Appointment',
            'book-your-ticket'         => 'Book Your Ticket',
            'booking-information'      => 'Booking Information',
            'choose-rent-option'       => 'Choose Rent Option',
            'closed'                   => 'Closed',
            'contact'                  => 'Contact',
            'daily-basis'              => 'Daily Basis',
            'date'                     => 'Date',
            'email'                    => 'Email',
            'event-on'                 => 'Event On',
            'friday'                   => 'Friday',
            'from'                     => 'From',
            'hourly-basis'             => 'Hourly Basis',
            'location'                 => 'Location',
            'monday'                   => 'Monday',
            'no-slots-available'       => 'No slots available',
            'number-of-tickets'        => 'Number of Tickets',
            'per-ticket-price'         => ':price Per Ticket',
            'rent-an-item'             => 'Rent an Item',
            'saturday'                 => 'Saturday',
            'select-date'              => 'Select date',
            'select-rent-time'         => 'Select Rent Time',
            'select-slot'              => 'Select Slot',
            'select-time-slot'         => 'Select time slot',
            'slot-duration-in-minutes' => ':minutes Minutes',
            'slot-duration'            => 'Slot Duration',
            'slot'                     => 'Slot',
            'slots-for-all-days'       => 'Show for all days',
            'special-notes'            => 'Special Request/Notes',
            'sunday'                   => 'Sunday',
            'thursday'                 => 'Thursday',
            'to'                       => 'To',
            'today-availability'       => 'Today Availability',
            'total-price'              => 'Total Price',
            'total-tickets'            => 'Total Tickets',
            'tuesday'                  => 'Tuesday',
            'view-on-map'              => 'View on Map',
            'wednesday'                => 'Wednesday',
        ],

        'cart' => [
            'booking-from' => 'Booking From',
            'booking-till' => 'Booking Till',
            'daily'        => 'Daily',
            'event-from'   => 'Event From',
            'event-ticket' => 'Event Ticket',
            'event-till'   => 'Event Till',

            'event'        => [
                'expired'  => 'This event has been expired.',
            ],

            'hourly'       => 'Hourly',

            'integrity'    => [
                'missing_fields'         => 'Some required fields missing for this product.',
                'missing_links'          => 'Downloadable links are missing for this product.',
                'missing_options'        => 'Options are missing for this product.',
                'qty_impossible'         => 'Cannot add more than one of these products to cart.',
                'qty_missing'            => 'Atleast one product should have more than 1 quantity.',
                'select_hourly_duration' => 'Select a slot duration of one hour.',
            ],

            'quantity'     => [
                'error'             => 'Cannot update the item(s) at the moment, please try again later.',
                'illegal'           => 'Quantity cannot be lesser than one.',
                'inventory_warning' => 'The requested quantity is not available, please try again later.',
                'quantity'          => 'Quantity',
                'success'           => 'Cart Item(s) successfully updated.',
            ],

            'rent-from'    => 'Rent From',
            'rent-till'    => 'Rent Till',
            'rent-type'    => 'Rent Type',
            'renting_type' => 'Rent Type',
            'special-note' => 'Special Request/Notes',
        ],
    ],

    'admin' => [
        'catalog' => [
            'products' => [
                'edit' => [
                    'type' => [
                        'booking' => [
                            'available-every-week'      => [
                                'no'    => 'No',
                                'title' => 'Available Every Week',
                                'yes'   => 'Yes',
                            ],

                            'action'                    => 'Actions',
                            'available-from'            => 'Available From',
                            'available-to'              => 'Available To',
                            'booking_type'              => 'Booking Type',
                            'break-duration'            => 'Break Time b/w Slots (Mins)',

                            'charged-per'               => [
                                'guest'  => 'Guest',
                                'table'  => 'Table',
                                'title'  => 'Charged Per',
                            ],

                            'default'                   => [
                                'close'  => 'Close',
                                'delete' => 'Delete',
                                'edit'   => 'Edit',
                                'many'   => 'Many',
                                'one'    => 'One',
                                'open'   => 'Open',
                                'title'  => 'Default',
                            ],

                            'event'                     => [
                                'action'             => 'Actions',
                                'description'        => 'Description',
                                'name'               => 'Name',
                                'price'              => 'Price',
                                'qty'                => 'QTY',
                                'special-price-from' => 'Special Price From',
                                'special-price-to'   => 'Special Price To',
                                'special-price'      => 'Special Price',
                                'valid-from'         => 'Valid From',
                                'valid-until'        => 'Valid Until',
                            ],

                            'from-day'                  => 'From Day',
                            'from'                      => 'From',
                            'guest-capacity'            => 'Guest Capacity',
                            'guest-limit'               => 'Guest Limit Per Table',
                            'location'                  => 'Location',

                            'modal'                     => [
                                'slot' => [
                                    'close'      => 'Close',
                                    'day'        => 'Day',
                                    'edit-title' => 'Edit Slots',
                                    'friday'     => 'Friday',
                                    'from-day'   => 'From Day',
                                    'from'       => 'From',
                                    'monday'     => 'Monday',
                                    'open'       => 'Open',
                                    'saturday'   => 'Saturday',
                                    'save'       => 'Save Slot',
                                    'select'     => 'Select',
                                    'status'     => 'Status',
                                    'sunday'     => 'Sunday',
                                    'thursday'   => 'Thursday',
                                    'time'       => 'Time',
                                    'title'      => 'Add Slots',
                                    'to'         => 'To',
                                    'tuesday'    => 'Tuesday',
                                    'wednesday'  => 'Wednesday',
                                    'week'       => ':day',
                                ],

                                'ticket'                => [
                                    'description'        => 'Description',
                                    'name'               => 'Name',
                                    'price'              => 'Price',
                                    'qty'                => 'Quantity',
                                    'save'               => 'Save Tickets',
                                    'special-price-from' => 'Special Price From',
                                    'special-price-to'   => 'Special Price To',
                                    'special-price'      => 'Special Price',
                                    'title'              => 'Add Tickets',
                                    'valid-from'         => 'Valid From',
                                    'valid-until'        => 'Valid Until',
                                ],
                            ],

                            'prevent-scheduling-before' => 'Prevent Scheduling Before',
                            'qty'                       => 'Qty',

                            'renting-type'              => [
                                'daily_hourly' => 'Both (Daily and Hourly Basis)',
                                'daily-price'  => 'Daily Price',
                                'daily'        => 'Daily Basis',
                                'hourly-price' => 'Hourly Price',
                                'hourly'       => 'Hourly Basis',
                                'title'        => 'Renting Type',
                            ],

                            'slot-duration'            => 'Slot Duration (Mins)',

                            'same-slot-for-all-days'   => [
                                'no'    => 'No',
                                'title' => 'Same Slot For All days',
                                'yes'   => 'Yes',
                            ],

                            'slots'                    => [
                                'add'         => 'Add Slots',
                                'delete'      => 'Delete',
                                'description' => 'There is no slots available.',
                                'edit'        => 'Edit',
                                'save'        => 'Save',
                                'title'       => 'Slots',
                            ],

                            'status'                   => 'Status',

                            'tickets'                  => [
                                'add'                => 'Add Tickets',
                                'delete'             => 'Delete',
                                'description'        => 'Description',
                                'description-n'      => 'There is no tickets available.',
                                'edit'               => 'Edit',
                                'name'               => 'Name',
                                'price'              => 'Price',
                                'qty'                => 'Quantity',
                                'special-price-from' => 'Special Price From',
                                'special-price-to'   => 'Special Price To',
                                'special-price'      => 'Special Price',
                                'title'              => 'Tickets',
                            ],

                            'type'                     => [
                                'appointment' => 'Appointment',
                                'default'     => 'Default',
                                'event'       => 'Event',
                                'many'        => 'Many',
                                'one'         => 'One',
                                'rental'      => 'Rental',
                                'table'       => 'Table',
                                'title'       => 'Type',
                            ],

                            'table'                    => [
                                'form' => ':from',
                                'to'   => ':to',
                            ],

                            'title'                    => 'Booking Information',
                            'to-day'                   => 'To Day',
                            'to'                       => 'To',
                        ],
                    ],
                ],

                'index' => [
                    'create' => [
                        'booking' => 'Booking',
                    ],
                ],
            ],
        ],

        'sales' => [
            'bookings' => [
                'index' => [
                    'datagrid' => [
                        'created-date' => 'Created Date',
                        'from'         => 'From',
                        'id'           => 'ID',
                        'order-id'     => 'Order ID',
                        'qty'          => 'QTY',
                        'to'           => 'To',
                    ],

                    'title' => 'Bookings Product',
                ],

                'title' => 'Bookings Product',
            ],
        ],
    ],
];
