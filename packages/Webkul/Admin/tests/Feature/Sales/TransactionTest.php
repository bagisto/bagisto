<?php

use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Sales\Generators\InvoiceSequencer;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Sales\Models\OrderTransaction;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

it('should return the index page of transactions', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.sales.transactions.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.transactions.index.title'));
});

it('should faild the validation error when store the transaction when certain inputs not provided', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    CartItem::factory()->create([
        'product_id' => $product->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
        'cart_id'    => $cartId = Cart::factory()->create([
            'customer_id'         => $customer->id,
            'customer_email'      => $customer->email,
            'customer_first_name' => $customer->first_name,
            'customer_last_name'  => $customer->last_name,
        ])->id,
    ]);

    $order = Order::factory()->create([
        'cart_id'             => $cartId,
        'customer_id'         => $customer->id,
        'customer_email'      => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
    ]);

    OrderItem::factory()->create([
        'product_id' => $product->id,
        'order_id'   => $order->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
    ]);

    $payment = OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    $customerAddress = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address1'     => $customerAddress->address1,
        'country'      => $customerAddress->country,
        'state'        => $customerAddress->state,
        'city'         => $customerAddress->city,
        'postcode'     => $customerAddress->postcode,
        'phone'        => $customerAddress->phone,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    $invoice = Invoice::factory([
        'order_id'         => $order->id,
        'sub_total'        => $order->grand_total,
        'base_sub_total'   => $order->grand_total,
        'grand_total'      => $order->grand_total,
        'base_grand_total' => $order->grand_total,
        'increment_id'     => app(InvoiceSequencer::class)->resolveGeneratorClass(),
    ])->create();

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.sales.transactions.store'))
        ->assertJsonValidationErrorFor('invoice_id')
        ->assertJsonValidationErrorFor('payment_method')
        ->assertJsonValidationErrorFor('amount')
        ->assertUnprocessable();
});

it('should store the order transaction', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    CartItem::factory()->create([
        'product_id' => $product->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
        'cart_id'    => $cartId = Cart::factory()->create([
            'customer_id'         => $customer->id,
            'customer_email'      => $customer->email,
            'customer_first_name' => $customer->first_name,
            'customer_last_name'  => $customer->last_name,
        ])->id,
    ]);

    $order = Order::factory()->create([
        'cart_id'             => $cartId,
        'customer_id'         => $customer->id,
        'customer_email'      => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
    ]);

    OrderItem::factory()->create([
        'product_id' => $product->id,
        'order_id'   => $order->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
    ]);

    $payment = OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    $customerAddress = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address1'     => $customerAddress->address1,
        'country'      => $customerAddress->country,
        'state'        => $customerAddress->state,
        'city'         => $customerAddress->city,
        'postcode'     => $customerAddress->postcode,
        'phone'        => $customerAddress->phone,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    $invoice = Invoice::factory([
        'order_id'         => $order->id,
        'sub_total'        => $order->grand_total,
        'base_sub_total'   => $order->grand_total,
        'grand_total'      => $order->grand_total,
        'base_grand_total' => $order->grand_total,
        'increment_id'     => app(InvoiceSequencer::class)->resolveGeneratorClass(),
    ])->create();

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.sales.transactions.store'), [
        'invoice_id'     => $invoice->id,
        'payment_method' => $payment->method,
        'amount'         => $order->grand_total,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.sales.transactions.index.create.transaction-saved'));

    $this->assertModelWise([
        OrderTransaction::class => [
            [
                'status'     => 'paid',
                'invoice_id' => $invoice->id,
                'order_id'   => $order->id,
            ],
        ],
    ]);
});

it('should view the transaction', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    CartItem::factory()->create([
        'product_id' => $product->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
        'cart_id'    => $cartId = Cart::factory()->create([
            'customer_id'         => $customer->id,
            'customer_email'      => $customer->email,
            'customer_first_name' => $customer->first_name,
            'customer_last_name'  => $customer->last_name,
        ])->id,
    ]);

    $order = Order::factory()->create([
        'grand_total'         => rand(111, 222),
        'cart_id'             => $cartId,
        'customer_id'         => $customer->id,
        'customer_email'      => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
    ]);

    OrderItem::factory()->create([
        'product_id' => $product->id,
        'order_id'   => $order->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
    ]);

    OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    $customerAddress = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address1'     => $customerAddress->address1,
        'country'      => $customerAddress->country,
        'state'        => $customerAddress->state,
        'city'         => $customerAddress->city,
        'postcode'     => $customerAddress->postcode,
        'phone'        => $customerAddress->phone,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    $invoice = Invoice::factory([
        'order_id'     => $order->id,
        'increment_id' => app(InvoiceSequencer::class)->resolveGeneratorClass(),
        'state'        => 'paid',
    ])->create();

    $transaction = OrderTransaction::create([
        'transaction_id' => md5(uniqid()),
        'status'         => $invoice->state,
        'type'           => $invoice->order->payment->method,
        'payment_method' => $invoice->order->payment->method,
        'order_id'       => $invoice->order->id,
        'invoice_id'     => $invoice->id,
        'amount'         => $invoice->grand_total,
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.sales.transactions.view', $transaction->id))
        ->assertOk()
        ->assertJsonPath('data.transaction.id', $transaction->id)
        ->assertJsonPath('data.transaction.payment_method', $transaction->payment_method)
        ->assertJsonPath('data.transaction.invoice_id', $transaction->invoice_id)
        ->assertJsonPath('data.transaction.order_id', $transaction->order_id);
});
