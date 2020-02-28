<?php

namespace Webkul\BookingProduct\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;

/**
 * BookingProduct Repository
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class BookingProductRepository extends Repository
{
    /**
     * @return array
     */
    protected $typeRepositories = [];

    /**
     * Create a new repository instance.
     *
     * @param Webkul\BookingProduct\Repositories\BookingProductDefaultSlotRepository     $bookingProductDefaultSlotRepository
     * @param Webkul\BookingProduct\Repositories\BookingProductAppointmentSlotRepository $bookingProductAppointmentSlotRepository
     * @param Webkul\BookingProduct\Repositories\BookingProductEventTicketRepository     $bookingProductEventTicketRepository
     * @param Webkul\BookingProduct\Repositories\BookingProductRentalSlotRepository      $bookingProductRentalSlotRepository
     * @param Webkul\BookingProduct\Repositories\BookingProductTableSlotRepository       $bookingProductTableSlotRepository
     * @return void
     */
    public function __construct(
        BookingProductDefaultSlotRepository $bookingProductDefaultSlotRepository,
        BookingProductAppointmentSlotRepository $bookingProductAppointmentSlotRepository,
        BookingProductEventTicketRepository $bookingProductEventTicketRepository,
        BookingProductRentalSlotRepository $bookingProductRentalSlotRepository,
        BookingProductTableSlotRepository $bookingProductTableSlotRepository,
        App $app
    )
    {
        parent::__construct($app);

        $this->typeRepositories['default'] = $bookingProductDefaultSlotRepository;

        $this->typeRepositories['appointment'] = $bookingProductAppointmentSlotRepository;

        $this->typeRepositories['event'] = $bookingProductEventTicketRepository;

        $this->typeRepositories['rental'] = $bookingProductRentalSlotRepository;

        $this->typeRepositories['table'] = $bookingProductTableSlotRepository;
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\BookingProduct\Contracts\BookingProduct';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $bookingProduct = parent::create($data);

        if ($bookingProduct->type == 'event') {
            $this->typeRepositories[$data['type']]->saveEventTickets($data, $bookingProduct);
        } else {
            $this->typeRepositories[$data['type']]->create(array_merge($data, ['booking_product_id' => $bookingProduct->id]));
        }

        return $bookingProduct;
    }

    /**
     * @param array   $data
     * @param integer $id
     * @param string  $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $bookingProduct = parent::update($data, $id, $attribute);

        foreach ($this->typeRepositories as $type => $repository) {
            if ($type == $data['type']) {
                continue;
            }

            $repository->deleteWhere(['booking_product_id' => $id]);
        }

        if ($bookingProduct->type == 'event') {
            $this->typeRepositories[$data['type']]->saveEventTickets($data, $bookingProduct);
        } else {
            $bookingProductTypeSlot = $this->typeRepositories[$data['type']]->findOneByField('booking_product_id', $id);

            if (isset($data['slots'])) {
                $data['slots'] = $this->validateSlots(
                                    $this->formatSlots($data)
                                );
            }

            if (! $bookingProductTypeSlot) {
                $this->typeRepositories[$data['type']]->create(array_merge($data, ['booking_product_id' => $id]));
            } else {
                $this->typeRepositories[$data['type']]->update($data, $bookingProductTypeSlot->id);
            }
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function formatSlots($data)
    {
        if (isset($data['slots']) && isset($data['same_slot_all_days']) && ! $data['same_slot_all_days']) {
            for ($i = 0; $i < 7; $i++) {
                if (! isset($data['slots'][$i])) {
                    $data['slots'][$i] = [];
                } else {
                    $count = 0;

                    $slots = [];

                    foreach ($data['slots'][$i] as $slot) {
                        $slots[] = array_merge($slot, ['id' => $i . '_slot_' . $count]);
                        
                        $count++;
                    }

                    $data['slots'][$i] = $slots;
                }
            }

            ksort($data['slots']);
        }

        return $data['slots'];
    }

    /**
     * @param array $data
     * @return array
     */
    public function validateSlots($data)
    {
        foreach ($data['slots'] as $key => $timeInterval) {
            
        }

        return $data;
    }
}