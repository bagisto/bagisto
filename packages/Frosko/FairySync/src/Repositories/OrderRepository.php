<?php

declare(strict_types=1);

namespace Frosko\FairySync\Repositories;

use Frosko\FairySync\Contracts\Repository;
use Frosko\FairySync\Models\Sync\Order;
use Illuminate\Support\Facades\DB;

class OrderRepository implements Repository
{
    public function addOrder(
        array $orderData,
        array $orderItems,
        array $addressData
    ): ?int {
        DB::connection('sync')->beginTransaction();
        try {
            /** @var Order $order */
            $order = Order::query()->create(
                $orderData
            );

            $order->orderItems()
                ->createMany($orderItems);
            $order->address()
                ->create($addressData);

            DB::connection('sync')->commit();

            return $order->id;
        } catch (\Exception $e) {
            DB::connection('sync')->rollBack();

            logger()->error('Unable to sync Order', [
                'e'         => $e->getMessage(),
                'order'     => $orderData,
                'items'     => $orderItems,
                'address'   => $addressData,
            ]);

            return null;
        }
    }

    public function prepareOrderAddressData(
        int $order_id,
        string $firstname,
        string $lastname,
        string $city,
        string $country,
        string $company,
        string $address_line_1,
        string $address_line_2,
        string $postcode,
        string $custom,

    ): array {
        return compact(
            'order_id',
            'firstname',
            'lastname',
            'city',
            'country',
            'company',
            'address_line_1',
            'address_line_2',
            'postcode',
            'custom',
        );
    }

    public function prepareOrderItemData(
        int $order_id,
        string $sku,
        int $quantity,
        string $price,
        string $total,
        string $tax,
    ): array {
        return compact(
            'order_id',
            'sku',
            'quantity',
            'price',
            'total',
            'tax'
        );
    }

    public function prepareOrderData(
        int $order_id,
        string $first_name,
        string $last_name,
        string $email,
        string $telephone,
        string $city,
        string $postal_code,
        string $country_code,
        string $order_date,
        int $pay_type,
        int $pay_method,
        string $sub_total,
        string $total,
        string $shipping_amount,
        string $discounts,
        string $currency,
        string $currency_rate,
        int $synced,
        ?array $errors = null,
        ?string $comment = null
    ): array {
        return [
            'order_id'        => $order_id,
            'first_name'      => $first_name,
            'last_name'       => $last_name,
            'email'           => $email,
            'telephone'       => $telephone,
            'city'            => $city,
            'postal_code'     => $postal_code,
            'country_code'    => $country_code,
            'pay_type'        => $pay_type,
            'pay_method'      => $pay_method,
            'sub_total'       => $sub_total,
            'total'           => $total,
            'shipping_amount' => $shipping_amount,
            'discounts'       => $discounts,
            'currency'        => $currency,
            'currency_rate'   => $currency_rate,
            'synced'          => $synced === 1 ? 0 : 1,
            'errors'          => $errors ?? null,
            'comment'         => $comment ?? null,
            'order_date'      => $order_date,
        ];
    }
}
