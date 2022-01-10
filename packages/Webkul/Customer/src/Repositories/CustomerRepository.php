<?php

namespace Webkul\Customer\Repositories;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;

class CustomerRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return \Webkul\Customer\Contracts\Customer::class;
    }

    /**
     * Create customer.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function create($attributes)
    {
        Event::dispatch('customer.registration.before');

        $customer = parent::create($attributes);

        Event::dispatch('customer.registration.after', $customer);

        return $customer;
    }

    /**
     * Update customer.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        Event::dispatch('customer.update.before');

        $customer = parent::update($attributes, $id);

        Event::dispatch('customer.update.after', $customer);

        return $customer;
    }

    /**
     * Check if customer has order pending or processing.
     *
     * @param  \Webkul\Customer\Models\Customer
     * @return boolean
     */
    public function checkIfCustomerHasOrderPendingOrProcessing($customer)
    {
        return $customer->all_orders->pluck('status')->contains(function ($val) {
            return $val === 'pending' || $val === 'processing';
        });
    }

    /**
     * Check if bulk customers, if they have order pending or processing.
     *
     * @param  array
     * @return boolean
     */
    public function checkBulkCustomerIfTheyHaveOrderPendingOrProcessing($customerIds)
    {
        foreach ($customerIds as $customerId) {
            $customer = $this->findorFail($customerId);

            if ($this->checkIfCustomerHasOrderPendingOrProcessing($customer)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Upload customer's images.
     *
     * @param  array  $data
     * @param  \Webkul\Customer\Models\Customer  $customer
     * @param  string $type
     * @return void
     */
    public function uploadImages($data, $customer, $type = 'image')
    {
        if (isset($data[$type])) {
            $request = request();

            foreach ($data[$type] as $imageId => $image) {
                $file = $type . '.' . $imageId;
                $dir = 'customer/' . $customer->id;

                if ($request->hasFile($file)) {
                    if ($customer->{$type}) {
                        Storage::delete($customer->{$type});
                    }

                    $customer->{$type} = $request->file($file)->store($dir);
                    $customer->save();
                }
            }
        } else {
            if ($customer->{$type}) {
                Storage::delete($customer->{$type});
            }

            $customer->{$type} = null;
            $customer->save();
        }
    }
}
