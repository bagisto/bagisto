<?php

namespace Webkul\Shop\Http\Controllers\Customer\Guest;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Webkul\RMA\Enums\RMA;
use Webkul\RMA\Repositories\RMAAdditionalFieldRepository;
use Webkul\RMA\Repositories\RMAImageRepository;
use Webkul\RMA\Repositories\RMAItemRepository;
use Webkul\RMA\Repositories\RMAMessageRepository;
use Webkul\RMA\Repositories\RMAReasonRepository;
use Webkul\RMA\Repositories\RMARepository;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Shop\DataGrids\RMA\CustomerRMADataGrid;
use Webkul\Shop\DataGrids\RMA\Guest\OrderRMADataGrid as GuestOrderRMADataGrid;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Shop\Mail\Customer\RMA\CustomerRMARequestNotification;

class GuestController extends Controller
{
    /**
     * @var string
     */
    public const CONFIGURABLE = 'configurable';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderItemRepository $orderItemRepository,
        protected OrderRepository $orderRepository,
        protected RMAAdditionalFieldRepository $rmaAdditionalFieldRepository,
        protected RMAImageRepository $rmaImagesRepository,
        protected RMAItemRepository $rmaItemsRepository,
        protected RMAMessageRepository $rmaMessagesRepository,
        protected RMAReasonRepository $rmaReasonsRepository,
        protected RMARepository $rmaRepository,
    ) {}

    /**
     * Method to populate the customer and guest rma index page.
     */
    public function index(): View|JsonResponse|RedirectResponse
    {
        if (request()->ajax()) {
            return datagrid(CustomerRMADataGrid::class)->process();
        }

        if (empty(session()->get('guestOrderId'))) {
            return redirect()->route('shop.rma.guest.session.index');
        }

        return view('shop::guest.rma.index');
    }

    /**
     * Get details of rma
     */
    public function view(int $id): View|RedirectResponse
    {
        $rma = $this->rmaRepository->with(['items', 'order'])->findOneWhere(['id' => $id]);

        if (! $rma) {
            return redirect()->route('shop.guest.account.rma.index');
        }

        return view('shop::guest.rma.view', compact('rma'));
    }

    /**
     * Create rma for guest
     */
    public function create(): RedirectResponse|View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(GuestOrderRMADataGrid::class)->process();
        }

        return view('shop::guest.rma.create');
    }

    /**
     * Store a newly created rma.
     */
    public function store(): JsonResponse|RedirectResponse
    {
        if (! is_array(request()->input('order_item_id'))) {
            session()->flash('warning', trans('Please select the item'));

            return redirect()->route('shop.guest.account.rma.create');
        }

        $this->validate(request(), [
            'rma_qty'         => 'required',
            'resolution_type' => 'required',
            'order_status'    => 'required',
            'images.*'        => 'nullable|file|mimetypes:'.core()->getConfigData('sales.rma.setting.allowed_file_extension'),
        ]);

        $data = request()->only([
            'order_id',
            'resolution_type',
            'order_status',
            'order_item_id',
            'rma_qty',
            'rma_reason_id',
            'images',
            'information',
        ]);

        if (request()->input('package_condition')) {
            $data['package_condition'] = request()->input('package_condition');
        }

        if (request()->input('return_pickup_address')) {
            $data['return_pickup_address'] = request()->input('return_pickup_address');
        }

        if (request()->input('return_pickup_time')) {
            $data['return_pickup_time'] = request()->input('return_pickup_time');
        }

        $rma = $this->rmaRepository->create([
            'status'                => '',
            'order_id'              => $data['order_id'],
            'information'           => $data['information'] ?? null,
            'order_status'          => $data['order_status'],
            'request_status'        => RMA::PENDING->value,
            'package_condition'     => $data['package_condition'] ?? '',
        ]);

        $data['order_items'] = [];

        foreach ($data['order_item_id'] as $key => $orderItemId) {
            $orderItem = $this->orderItemRepository->find($orderItemId);

            array_push($data['order_items'], $orderItem);

            $rmaItem = $this->rmaItemsRepository->create([
                'resolution'    => $data['resolution_type'][$key],
                'rma_id'        => $rma->id,
                'order_item_id' => $orderItemId,
                'quantity'      => $data['rma_qty'][$key],
                'rma_reason_id' => $data['rma_reason_id'][$key],
                'variant_id'    => ! empty($data['variant'][$key]) ? $data['variant'][$key] : null,
            ]);

            $rmaItemIds[] = $rmaItem->id;

            $data['reason'][] = $this->rmaReasonsRepository->findOneWhere(['id' => $data['rma_reason_id'][$key]])->title;
        }

        $this->rmaMessagesRepository->create([
            'message'    => trans('shop::app.rma.mail.customer-conversation.process'),
            'rma_id'     => $rma->id,
            'is_admin'   => 1,
        ]);

        $data['rma_id'] = $rma->id;

        if (! empty($data['images']) && ! empty(implode(',', $data['images']))) {
            foreach ($data['images'] as $itemImg) {
                $this->rmaImagesRepository->create([
                    'rma_id' => $rma->id,
                    'path'   => ! empty($itemImg) ?? $itemImg->getClientOriginalName(),
                ]);
            }

            // Save the image in the public path
            $this->rmaImagesRepository->uploadImages($data, $rma);
        }

        // Save custom Attributes
        $customAttributes = request('customAttributes') ?? [];

        if ($customAttributes) {

            $customAttributesData = [];

            foreach ($customAttributes as $key => $customAttribute) {

                $customAttributesData = [
                    'rma_id' => $data['rma_id'],
                    'name'   => $key,
                    'value'  => is_array($customAttribute) ? implode(',', $customAttribute) : $customAttribute,
                ];

                $this->rmaAdditionalFieldRepository->create($customAttributesData);
            }
        }

        $order = $this->orderRepository->findOrFail($rma->order_id);

        $orderData = $order->items->whereIn('id', $rmaItemIds);

        foreach ($orderData as $key => $configurableProducts) {
            if ($configurableProducts['type'] == self::CONFIGURABLE) {
                $data['skus'][] = $configurableProducts['child'];
            }
        }

        $data['email'] = $order->customer_email;

        $data['name'] = $order->customer_first_name.' '.$order->customer_last_name;

        unset($data['images']);

        if ($rma->items) {
            try {
                Mail::queue(new CustomerRMARequestNotification($data));

            } catch (\Exception $e) {
                \Log::error('Error in Sending Email'.$e->getMessage());
            }

            return new JsonResponse([
                'messages' => trans('shop::app.rma.response.create-success'),
            ]);
        }

        return new JsonResponse([
            'messages' => trans('shop::app.customer.signup-form.failed'),
        ]);
    }
}
