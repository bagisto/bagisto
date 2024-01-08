<?php

return [
    'admin' => [
        'catalog' => [
            'products' => [
                'edit'  => [
                    'booking' => [
                        'appointment' => [
                            'break-duration'         => 'Temps de pause entre les créneaux (minutes)',
                            'slot-duration'          => 'Durée du créneau (minutes)',

                            'same-slot-for-all-days' => [
                                'no'    => 'Non',
                                'title' => 'Même créneau pour tous les jours',
                                'yes'   => 'Oui',
                            ],
                        ],

                        'default'     => [
                            'break-duration' => 'Temps de pause entre les créneaux (minutes)',
                            'close'          => 'Fermer',
                            'delete'         => 'Supprimer',
                            'description'    => 'Informations de réservation',
                            'edit'           => 'Modifier',
                            'many'           => 'Beaucoup',

                            'modal'          => [
                                'slot' => [
                                    'close'      => 'Fermer',
                                    'day'        => 'Jour',
                                    'edit-title' => 'Modifier les créneaux',
                                    'friday'     => 'Vendredi',
                                    'from-day'   => 'Du jour',
                                    'from'       => 'De',
                                    'monday'     => 'Lundi',
                                    'open'       => 'Ouvrir',
                                    'saturday'   => 'Samedi',
                                    'save'       => 'Enregistrer le créneau',
                                    'select'     => 'Sélectionner',
                                    'status'     => 'Statut',
                                    'sunday'     => 'Dimanche',
                                    'thursday'   => 'Jeudi',
                                    'time'       => 'Temps',
                                    'title'      => 'Ajouter des créneaux',
                                    'to'         => 'À',
                                    'tuesday'    => 'Mardi',
                                    'wednesday'  => 'Mercredi',
                                    'week'       => ':day',
                                ],
                            ],

                            'one'            => 'Un',
                            'open'           => 'Ouvert',
                            'slot-duration'  => 'Durée du créneau (minutes)',
                            'title'          => 'Défaut',
                        ],

                        'event'       => [
                            'add'                => 'Ajouter des billets',
                            'delete'             => 'Supprimer',
                            'description-info'   => 'Aucun billet disponible.',
                            'description'        => 'Description',
                            'edit'               => 'Modifier',

                            'modal'              => [
                                'ticket' => [
                                    'save' => 'Enregistrer les billets',
                                ],
                            ],

                            'name'               => 'Nom',
                            'price'              => 'Prix',
                            'qty'                => 'Quantité',
                            'special-price-from' => 'Prix spécial à partir de',
                            'special-price-to'   => 'Prix spécial jusqu’à',
                            'special-price'      => 'Prix spécial',
                            'title'              => 'Billets',
                        ],

                        'empty-info'  => [
                            'tickets' => [
                                'add' => 'Ajouter des billets',
                            ],

                            'slots'   => [
                                'add'         => 'Ajouter des créneaux',
                                'delete'      => 'Supprimer',
                                'description' => 'Créneaux disponibles avec une durée.',
                            ],
                        ],

                        'rental'      => [
                            'daily_hourly'           => 'Les deux (quotidien et horaire)',
                            'daily-price'            => 'Prix quotidien',
                            'daily'                  => 'Quotidien',
                            'hourly-price'           => 'Prix horaire',
                            'hourly'                 => 'Horaire',

                            'same-slot-for-all-days' => [
                                'no'    => 'Non',
                                'title' => 'Même créneau pour tous les jours',
                                'yes'   => 'Oui',
                            ],

                            'title'                  => 'Type de location',
                        ],

                        'slots'       => [
                            'add'              => 'Ajouter des créneaux',
                            'delete'           => 'Supprimer',
                            'description-info' => 'Créneaux disponibles avec une durée.',
                            'description'      => 'Aucun créneau disponible.',
                            'edit'             => 'Modifier',

                            'modal'            => [
                                'slot' => [
                                    'friday'     => 'Vendredi',
                                    'from'       => 'De',
                                    'monday'     => 'Lundi',
                                    'saturday'   => 'Samedi',
                                    'sunday'     => 'Dimanche',
                                    'thursday'   => 'Jeudi',
                                    'to'         => 'À',
                                    'tuesday'    => 'Mardi',
                                    'wednesday'  => 'Mercredi',
                                ],
                            ],

                            'save'             => 'Enregistrer',
                            'title'            => 'Créneaux',
                        ],

                        'table'       => [
                            'break-duration'            => 'Temps de pause entre les créneaux (minutes)',

                            'charged-per'               => [
                                'guest'  => 'Invité',
                                'table'  => 'Table',
                                'title'  => 'Facturé par',
                            ],

                            'guest-capacity'            => 'Capacité d’invités',
                            'guest-limit'               => 'Limite d’invités par table',
                            'prevent-scheduling-before' => 'Empêcher la planification avant',
                            'slot-duration'             => 'Durée du créneau (minutes)',

                            'same-slot-for-all-days'    => [
                                'no'    => 'Non',
                                'title' => 'Même créneau pour tous les jours',
                                'yes'   => 'Oui',
                            ],
                        ],
                    ],

                    'types'   => [
                        'booking' => [
                            'available-from' => 'Disponible à partir de',
                            'available-to'   => 'Disponible jusqu’à',

                            'available-every-week'      => [
                                'no'    => 'Non',
                                'title' => 'Disponible chaque semaine',
                                'yes'   => 'Oui',
                            ],

                            'location' => 'Emplacement',
                            'qty'      => 'Quantité',

                            'type' => [
                                'appointment' => 'Rendez-vous',
                                'default'     => 'Défaut',
                                'event'       => 'Événement',
                                'many'        => 'Beaucoup',
                                'one'         => 'Un',
                                'rental'      => 'Location',
                                'table'       => 'Table',
                                'title'       => 'Type',
                            ],

                            'title' => 'Type de Réservation',
                        ],
                    ],
                ],

                'index' => [
                    'booking' => 'Réservation',
                ],
            ],
        ],

        'sales'   => [
            'bookings' => [
                'index' => [
                    'datagrid' => [
                        'created-date' => 'Date de création',
                        'from'         => 'De',
                        'id'           => 'ID',
                        'order-id'     => 'ID de commande',
                        'qty'          => 'Qté',
                        'to'           => 'À',
                    ],

                    'title' => 'Produit de Réservations',
                ],

                'title' => 'Produit de Réservations',
            ],
        ],
    ],

    'shop'  => [
        'products' => [
            'booking' => [
                'closed'           => 'Fermé',

                'cart'             => [
                    'booking-from' => 'Réservation à partir de',
                    'booking-till' => 'Réservation jusqu\'à',
                    'daily'        => 'Quotidien',
                    'event-from'   => 'Événement à partir de',
                    'event-ticket' => 'Billet d\'événement',
                    'event-till'   => 'Événement jusqu\'à',

                    'integrity'    => [
                        'missing_options'        => 'Options manquantes pour ce produit.',
                        'select_hourly_duration' => 'Sélectionnez une durée de créneau d\'une heure.',
                    ],

                    'rent-from'    => 'Louer à partir de',
                    'rent-till'    => 'Louer jusqu\'à',
                    'rent-type'    => 'Type de location',
                    'renting_type' => 'Type de location',
                    'special-note' => 'Demande spéciale/Notes',
                ],

                'per-ticket-price' => ':price Par Billet',
            ],

            'view'    => [
                'types'   => [
                    'booking' => [
                        'event-on'                 => 'Événement le',
                        'location'                 => 'Emplacement',
                        'slot-duration-in-minutes' => ':minutes minutes',
                        'slot-duration'            => 'Durée de créneau',
                        'view-on-map'              => 'Voir sur la carte',
                    ],
                ],

                'booking' => [
                    'appointment' => [
                        'closed'             => 'Fermé',
                        'today-availability' => "Disponibilité d'aujourd'hui",
                    ],

                    'event'       => [
                        'book-your-ticket' => 'Réservez votre billet',
                    ],

                    'rental'      => [
                        'choose-rent-option' => 'Choisissez une option de location',
                        'daily-basis'        => 'Journalier',
                        'from'               => 'De',
                        'hourly-basis'       => 'Horaire',
                        'rent-an-item'       => 'Louer un article',
                        'select-date'        => 'Sélectionner une date',
                        'select-rent-time'   => 'Sélectionnez l\'heure de location',
                        'select-slot'        => 'Sélectionner un créneau',
                        'slot'               => 'Créneau',
                        'to'                 => 'À',
                    ],

                    'slots'       => [
                        'book-an-appointment' => 'Prendre un rendez-vous',
                        'date'                => 'Date',
                        'no-slots-available'  => 'Aucun créneau disponible',
                        'title'               => 'Créneau',
                    ],

                    'table'       => [
                        'book-a-table'       => 'Réservez une table',
                        'closed'             => 'Fermé',
                        'slots-for-all-days' => 'Afficher pour tous les jours',
                        'special-notes'      => 'Demandes spéciales/Remarques',
                        'today-availability' => "Disponibilité d'aujourd'hui",
                    ],
                ],
            ],
        ],
    ],
];
