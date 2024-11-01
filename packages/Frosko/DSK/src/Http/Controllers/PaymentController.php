<?php

namespace Frosko\DSK\Http\Controllers;

use App\Http\Controllers\Controller;
use Frosko\DSK\Enums\Currency;
use Frosko\DSK\Enums\Locale;
use Frosko\DSK\Enums\PaymentStatus;
use Frosko\DSK\Exceptions\DskBankException;
use Frosko\DSK\Services\DskBankService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderTransaction;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;
use Webkul\Sales\Transformers\OrderResource;

final class PaymentController extends Controller
{
    public function __construct(
        private readonly DskBankService $dskService,
        private readonly OrderRepository $orderRepository,
        private readonly InvoiceRepository $invoiceRepository,
        private readonly OrderTransactionRepository $orderTransactionRepository,
    ) {}

    public function redirect(Request $request): RedirectResponse
    {

        if ($order = $this->orderRepository->findOneWhere(['cart_id' => cart()->getCart()->id])) {
            $this->orderRepository->update(['status' => Order::STATUS_CLOSED], $order->id);
        }

        // 2nd create new order for cart
        $orderId = $this->createOrderForCart();

        try {
            $result = $this->dskService->registerPayment(
                amount: cart()->getCart()->grand_total * 100,
                currency: Currency::tryByCode(core()->getCurrentCurrency()->code),
                orderNumber: $orderId,
                description: 'Order Payment for order '.$orderId,
                locale: Locale::tryByCode(core()->getCurrentLocale()->code),
            );

            if ($result['orderId'] && $result['formUrl']) {
                $this->createTransaction($orderId, $result['orderId']);

                return redirect()->away($result['formUrl']);
            }

            session()->flash('error', 'The Payment with DSK Bank was not successful.');

            return redirect()->route('shop.checkout.cart.index');
        } catch (DskBankException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * failure
     */
    public function failure()
    {
        session()->flash('error', 'The Payment with DSK Bank was either cancelled or transaction failed.');

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Success payment.
     */
    public function success(Request $request): RedirectResponse
    {
        $transaction = $this->orderTransactionRepository->findOneWhere([
            'transaction_id' => $request->input('orderId'),
            'status'         => 'pending',
        ]);

        if (! $transaction) {
            session()->flash('error', 'The Payment with DSK Bank was not successful.');

            return redirect()->route('shop.checkout.cart.index');
        }

        /** @var Order $order */
        $order = $transaction->order;
        $order->update(['status' => Order::STATUS_PENDING_PAYMENT]);

        if ($order->canInvoice()) {
            $invoice = $this->invoiceRepository->create($this->prepareInvoiceData($order));
            $transaction->update(['invoice_id' => $invoice->id]);
        }

        cart()->deActivateCart();
        session()->flash('order_id', $order->id); // line instead of $order in v2.1

        // Order and prepare invoice
        return redirect()->route('shop.checkout.onepage.success');
    }

    protected function prepareInvoiceData($order): array
    {
        $invoiceData = ['order_id' => $order->id];

        foreach ($order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }

    public function ipn(Request $request): JsonResponse
    {
        try {
            $result = $this->dskService->processWebhook(
                $request->input('orderId'),
                $request->input('orderNumber')
            );

            /** @var OrderTransaction $order */
            $transaction = $this->orderTransactionRepository->findOneWhere([
                'transaction_id' => $result['orderId'],
                'status'         => 'pending',
            ]);

            if (! $transaction) {
                return response()->json(['error' => 'Transaction not found'], 400);
            }

            $status = PaymentStatus::frowmValue($result['orderStatus']);

            if (in_array($status, [
                PaymentStatus::APPROVED,
                PaymentStatus::DEPOSITED,
                PaymentStatus::DECLINED,
                PaymentStatus::REVERSED,
                PaymentStatus::TIMEOUT,
            ])) {
                $this->orderRepository->update(['status' => match ($status) {
                    PaymentStatus::APPROVED  => Order::STATUS_PROCESSING,
                    PaymentStatus::DEPOSITED => Order::STATUS_COMPLETED,
                    default                  => Order::STATUS_CANCELED,
                }], $transaction->order->id);
            }

            return response()->json([
                'status' => $status?->label(),
                'data'   => $result,
            ]);
        } catch (DskBankException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    private function createTransaction(int $orderId, string $transactionId): void
    {
        $this->orderTransactionRepository->create([
            'transaction_id' => $transactionId,
            'status'         => 'pending',
            'type'           => 'payment',
            'amount'         => cart()->getCart()->grand_total,
            'payment_method' => 'dsk',
            'order_id'       => $orderId,
            'invoice_id'     => 0,
        ]);
    }

    private function createOrderForCart(): int
    {
        $order = $this->orderRepository->create((new OrderResource(cart()->getCart()))->jsonSerialize());
        $this->orderRepository->update(['status' => Order::STATUS_PENDING], $order->id);
        if ($order->canInvoice()) {
            $this->invoiceRepository->create($this->prepareInvoiceData($order));
        }

        return $order->id;
    }
}
