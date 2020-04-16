<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddTableAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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

            $table->timestamps();

            $table->foreign(['customer_id'])->references('id')->on('customers');
            $table->foreign(['cart_id'])->references('id')->on('cart');
            $table->foreign(['order_id'])->references('id')->on('orders');
        });

        $this->insertCustomerAddresses();
        $this->insertCartAddresses();
        $this->insertOrderAddresses();

        Schema::drop('customer_addresses');
        Schema::drop('cart_address');
        Schema::drop('order_address');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        throw new Exception('you cannot revert this migration: data would be lost');
    }

    private function insertCustomerAddresses(): void
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
                created_at,
                updated_at
            )
            SELECT
                "customer",
                customer_id,
                first_name,
                last_name,
                (SELECT gender FROM customers c WHERE c.id=ca.customer_id),
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
                created_at,
                updated_at
            FROM customer_addresses ca;
SQL;

        DB::unprepared($insertCustomerAddresses);
    }

    private function insertCartAddresses(): void
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
                default_address,
                created_at,
                updated_at
            )
            SELECT
                (CASE ca.address_type='billing' THEN "cart_address_billing" ELSE "cart_address_shipping" END),
                customer_id,
                cart_id,
                first_name,
                last_name,
                (SELECT gender FROM customers c WHERE c.id=ca.customer_id),
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
                created_at,
                updated_at
            FROM cart_address ca;
SQL;

        DB::unprepared($insertCustomerAddresses);
    }

    private function insertOrderAddresses(): void
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
                default_address,
                created_at,
                updated_at
            )
            SELECT
                (CASE ca.address_type='billing' THEN "order_address_billing" ELSE "order_address_shipping" END),
                customer_id,
                order_id,
                first_name,
                last_name,
                (SELECT gender FROM customers c WHERE c.id=os.customer_id),
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
                created_at,
                updated_at
            FROM order_address oa;
SQL;

        DB::unprepared($insertCustomerAddresses);
    }
}
