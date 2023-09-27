<?php

namespace Webkul\Customer\Repositories;

use Carbon\Carbon;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;
use Webkul\Sales\Models\Order;

class CustomerRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return 'Webkul\Customer\Contracts\Customer';
    }

    /**
     * Check if customer has order pending or processing.
     *
     * @param  \Webkul\Customer\Models\Customer
     * @return boolean
     */
    public function checkIfCustomerHasOrderPendingOrProcessing($customer)
    {
        return $customer->orders->pluck('status')->contains(function ($val) {
            return $val === 'pending' || $val === 'processing';
        });
    }

    /**
     * Returns current customer group
     *
     * @return \Webkul\Customer\Models\CustomerGroup
     */
    public function getCurrentGroup()
    {
        if ($customer = auth()->guard()->user()) {
            return $customer->group;
        }

        return core()->getGuestCustomerGroup();
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
            $customer = $this->findOrFail($customerId);

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

    /**
     * Sync new registered customer data.
     *
     * @param  \Webkul\Customer\Contracts\Customer  $customer
     * @return mixed
     */
    public function syncNewRegisteredCustomerInformation($customer)
    {
        /**
         * Setting registered customer to orders.
         */
        Order::where('customer_email', $customer->email)->update([
            'is_guest'      => 0,
            'customer_id'   => $customer->id,
            'customer_type' => \Webkul\Customer\Models\Customer::class,
        ]);

        /**
         * Grabbing orders by `customer_id`.
         */
        $orders = Order::where('customer_id', $customer->id)->get();

        /**
         * Setting registered customer to associated order's relations.
         */
        $orders->each(function ($order) use ($customer) {
            $order->addresses()->update([
                'customer_id' => $customer->id,
            ]);

            $order->shipments()->update([
                'customer_id'   => $customer->id,
                'customer_type' => \Webkul\Customer\Models\Customer::class,
            ]);

            $order->downloadable_link_purchased()->update([
                'customer_id' => $customer->id,
            ]);
        });
    }

    /**
     * Get customers count by date.
     */
    public function getCustomersCountByDate(?Carbon $from = null, Carbon $to = null): ?int
    {
        if ($from && $to) {
            return $this->count([['created_at', '>=', $from], ['created_at', '<=', $to]]);
        }

        if ($from) {
            return $this->count([['created_at', '>=', $from]]);
        }

        if ($to) {
            return $this->count([['created_at', '<=', $to]]);
        }

        return $this->count();
    }
}
