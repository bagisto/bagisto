<?php

use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Shipment;
use Illuminate\Support\Facades\DB;
use Webkul\Sales\Models\OrderAddress;
use Illuminate\Support\Facades\Schema;
use Webkul\Checkout\Models\CartAddress;
use Illuminate\Database\Schema\Blueprint;
use Webkul\Checkout\Models\CartShippingRate;
use Illuminate\Database\Migrations\Migration;

class AddTableAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create('addresses', function (Blueprint $table) {
                $table->increments('id');
                $table->string('address_type');
                $table->unsignedInteger('customer_id')->nullable()->comment('null if guest checkout');
                $table->unsignedInteger('cart_id')->nullable()->comment('only for cart_addresses');
                $table->unsignedInteger('order_id')->nullable()->comment('only for order_addresses');

                $table->string('first_name');
                $table->string('last_name');
                $table->string('gender')->nullable();
                $table->string('company_name')->nullable();
                $table->string('address1');
                $table->string('address2')->nullable();
                $table->string('postcode');
                $table->string('city');
                $table->string('state');
                $table->string('country');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();

                $table->string('vat_id')->nullable();
                $table->boolean('default_address')
                    ->default(false)
                    ->comment('only for customer_addresses');

                $table->json('additional')->nullable();

                $table->timestamps();

                $table->foreign(['customer_id'])->references('id')->on('customers')->onDelete('cascade');
                $table->foreign(['cart_id'])->references('id')->on('cart')->onDelete('cascade');
                $table->foreign(['order_id'])->references('id')->on('orders')->onDelete('cascade');
            });

            Schema::disableForeignKeyConstraints();

            $this->migrateCustomerAddresses();
            $this->migrateCartAddresses();
            $this->migrateOrderAddresses();

            $this->migrateForeignKeys();

            Schema::drop('customer_addresses');
            Schema::drop('cart_address');
            Schema::drop('order_address');

            Schema::enableForeignKeyConstraints();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        throw new Exception('You cannot revert this migration. Data would be lost.');
    }

    private function migrateCustomerAddresses(): void
    {
        $dbPrefix = DB::getTablePrefix();

        $insertCustomerAddresses = <<< SQL
            INSERT INTO ${dbPrefix}addresses(
                address_type,
                customer_id,
                first_name,
                last_name,
                gender,
                company_name,
                address1,
                address2,
                postcode,
                city,
                state,
                country,
                email,
                phone,
                default_address,
                additional,
                created_at,
                updated_at
            )
            SELECT
                "customer",
                customer_id,
                first_name,
                last_name,
                (SELECT gender FROM ${dbPrefix}customers c WHERE c.id=ca.customer_id),
                company_name,
                address1,
                address2,
                postcode,
                city,
                state,
                country,
                null,
                phone,
                default_address,
                JSON_INSERT('{}', '$.old_customer_address_id', id),
                created_at,
                updated_at
            FROM ${dbPrefix}customer_addresses ca;
SQL;

        DB::unprepared($insertCustomerAddresses);
    }

    private function migrateCartAddresses(): void
    {
        $dbPrefix = DB::getTablePrefix();

        $insertCustomerAddresses = <<< SQL
            INSERT INTO ${dbPrefix}addresses(
                address_type,
                customer_id,
                cart_id,
                first_name,
                last_name,
                gender,
                company_name,
                address1,
                address2,
                postcode,
                city,
                state,
                country,
                email,
                phone,
                additional,
                created_at,
                updated_at
            )
            SELECT
                (CASE WHEN ca.address_type='billing' THEN "cart_billing" ELSE "cart_shipping" END),
                customer_id,
                cart_id,
                first_name,
                last_name,
                (SELECT gender FROM ${dbPrefix}customers c WHERE c.id=ca.customer_id),
                company_name,
                address1,
                address2,
                postcode,
                city,
                state,
                country,
                email,
                phone,
                JSON_INSERT('{}', '$.old_cart_address_id', id),
                created_at,
                updated_at
            FROM ${dbPrefix}cart_address ca;
SQL;

        DB::unprepared($insertCustomerAddresses);
    }

    private function migrateOrderAddresses(): void
    {
        $dbPrefix = DB::getTablePrefix();

        $insertCustomerAddresses = <<< SQL
            INSERT INTO ${dbPrefix}addresses(
                address_type,
                customer_id,
                order_id,
                first_name,
                last_name,
                gender,
                company_name,
                address1,
                address2,
                postcode,
                city,
                state,
                country,
                email,
                phone,
                additional,
                created_at,
                updated_at
            )
            SELECT
                (CASE WHEN oa.address_type='billing' THEN "order_billing" ELSE "order_shipping" END),
                customer_id,
                order_id,
                first_name,
                last_name,
                (SELECT gender FROM ${dbPrefix}customers c WHERE c.id=oa.customer_id),
                company_name,
                address1,
                address2,
                postcode,
                city,
                state,
                country,
                email,
                phone,
                JSON_INSERT('{}', '$.old_order_address_id', id),
                created_at,
                updated_at
            FROM ${dbPrefix}order_address oa;
SQL;

        DB::unprepared($insertCustomerAddresses);
    }

    private function migrateForeignKeys(): void
    {
        Schema::table('cart_shipping_rates', static function (Blueprint $table) {
            $table->dropForeign(['cart_address_id']);

            CartAddress::query()
                ->orderBy('id', 'asc') // for some reason each() needs an orderBy in before
                ->each(static function ($row) {
                    CartShippingRate::query()
                        ->where('cart_address_id', $row->additional['old_cart_address_id'])
                        ->update(['cart_address_id' => $row->id]);
                });

            $table->foreign(['cart_address_id'])
                ->references('id')
                ->on('addresses')
                ->onDelete('cascade');
        });

        Schema::table('invoices', static function (Blueprint $table) {
            $table->dropForeign(['order_address_id']);

            OrderAddress::query()
                ->orderBy('id', 'asc') // for some reason each() needs an orderBy in before
                ->each(static function ($row) {
                    Invoice::query()
                        ->where('order_address_id', $row->additional['old_order_address_id'])
                        ->update(['order_address_id' => $row->id]);
                });

            $table->foreign(['order_address_id'])
                ->references('id')
                ->on('addresses')
                ->onDelete('cascade');
        });

        Schema::table('shipments', static function (Blueprint $table) {
            $table->dropForeign(['order_address_id']);

            OrderAddress::query()
                ->orderBy('id', 'asc') // for some reason each() needs an orderBy in before
                ->each(static function ($row) {
                    Shipment::query()
                        ->where('order_address_id', $row->additional['old_order_address_id'])
                        ->update(['order_address_id' => $row->id]);
                });

            $table->foreign(['order_address_id'])
                ->references('id')
                ->on('addresses')
                ->onDelete('cascade');
        });
    }
}
