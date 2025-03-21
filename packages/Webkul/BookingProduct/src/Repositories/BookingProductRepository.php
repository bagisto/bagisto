<?php

namespace Webkul\BookingProduct\Repositories;

use Carbon\Carbon;
use Illuminate\Container\Container;
use Webkul\BookingProduct\Contracts\BookingProduct;
use Webkul\Core\Eloquent\Repository;

class BookingProductRepository extends Repository
{
    /**
     * @return array
     */
    protected $typeRepositories = [];

    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected BookingProductDefaultSlotRepository $bookingProductDefaultSlotRepository,
        protected BookingProductAppointmentSlotRepository $bookingProductAppointmentSlotRepository,
        protected BookingProductEventTicketRepository $bookingProductEventTicketRepository,
        protected BookingProductRentalSlotRepository $bookingProductRentalSlotRepository,
        protected BookingProductTableSlotRepository $bookingProductTableSlotRepository,
        Container $container
    ) {
        parent::__construct($container);

        $this->typeRepositories = [
            'default'     => $bookingProductDefaultSlotRepository,
            'appointment' => $bookingProductAppointmentSlotRepository,
            'event'       => $bookingProductEventTicketRepository,
            'rental'      => $bookingProductRentalSlotRepository,
            'table'       => $bookingProductTableSlotRepository,
        ];
    }

    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return BookingProduct::class;
    }

    /**
     * @return BookingProduct
     */
    public function create(array $data)
    {
        if (isset($data['slots'])) {
            $data['slots'] = $this->validateSlots($data);
        }

        $bookingProduct = parent::create($data);

        if ($bookingProduct->type == 'event') {
            $this->typeRepositories[$data['type']]->saveEventTickets($data, $bookingProduct);
        } else {
            $this->typeRepositories[$data['type']]->create(array_merge($data, ['booking_product_id' => $bookingProduct->id]));
        }

        return $bookingProduct;
    }

    /**
     * Update method.
     *
     * @param  int  $id
     * @param  string  $attribute
     * @return BookingProduct|void
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        if (isset($data['slots'])) {
            $data['slots'] = $this->skipOverLappingSlots($data['slots']);
        }

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
                $data['slots'] = $this->formatSlots($data);

                $data['slots'] = $this->validateSlots($data);
            } else {
                $data['slots'] = $this->addSlots($data);
            }

            if (! $bookingProductTypeSlot) {
                $this->typeRepositories[$data['type']]->create(array_merge($data, ['booking_product_id' => $id]));
            } else {
                $this->typeRepositories[$data['type']]->update($data, $bookingProductTypeSlot->id);
            }
        }
    }

    /**
     * Format Slots data.
     */
    public function formatSlots(array $data): array
    {
        if (
            isset($data['same_slot_all_days'])
            && ! $data['same_slot_all_days']
        ) {
            for ($i = 0; $i < 7; $i++) {
                if (! isset($data['slots'][$i])) {
                    $data['slots'][$i] = [];
                } else {
                    $count = 0;

                    $slots = [];

                    foreach ($data['slots'][$i] as $slot) {
                        $slots[] = array_merge($slot, ['id' => $i.'_slot_'.$count]);

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
     * Add blank array where slots key in available.
     */
    public function addSlots(array $data): array
    {
        if (isset($data['same_slot_all_days']) && ! $data['same_slot_all_days']) {
            return [[], [], [], [], [], [], []];
        } else {
            return (
                $data['type'] == 'default'
                && $data['booking_type'] == 'many'
            )
                ? [[], [], [], [], [], [], []]
                : [];
        }
    }

    /**
     * Validate Slots data.
     */
    public function validateSlots(array $data): array
    {
        if (! isset($data['same_slot_all_days'])) {
            return $data['slots'];
        }

        if (! $data['same_slot_all_days']) {
            foreach ($data['slots'] as $day => $slots) {
                $data['slots'][$day] = $this->skipOverLappingSlots($slots);
            }
        } else {
            $data['slots'] = $this->skipOverLappingSlots($data['slots']);
        }

        return $data['slots'];
    }

    /**
     * Filters out overlapping time slots from a given array.
     * Supports both flat arrays and nested arrays of time slots.
     */
    public function skipOverLappingSlots(array $slots): array
    {
        $filteredSlots = [];

        foreach ($slots as $key => $slot) {
            if (isset($slot[0]) && is_array($slot[0])) {
                $filteredSlots[$key] = $this->processSlots($slot);
            } else {
                $filteredSlots = array_merge($filteredSlots, $this->processSlots([$slot]));
            }
        }

        return $filteredSlots;
    }

    /**
     * Processes a list of time slots to remove overlapping intervals.
     */
    private function processSlots(array $slots): array
    {
        $tempSlots = [];

        $validSlots = [];

        foreach ($slots as $key => $timeInterval) {
            $from = Carbon::createFromTimeString($timeInterval['from'])->getTimestamp();

            $to = Carbon::createFromTimeString($timeInterval['to'])->getTimestamp();

            if ($from > $to) {
                continue;
            }

            $isOverLapping = false;

            foreach ($tempSlots as $slot) {
                if (
                    (
                        $slot['from'] <= $from
                        && $slot['to'] >= $from
                    )
                    || (
                        $slot['from'] <= $to
                        && $slot['to'] >= $to
                    )
                ) {
                    $isOverLapping = true;
                    break;
                }
            }

            if (! $isOverLapping) {
                $tempSlots[] = [
                    'from' => $from,
                    'to'   => $to,
                ];

                $validSlots[] = $timeInterval;
            }
        }

        return $validSlots;
    }
}
