<?php

namespace Webkul\Admin\Http\Controllers\Sales\RMA;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Sales\RMA\OrderRMADataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\RMA\Contracts\ReasonResolution;
use Webkul\RMA\Helpers\Helper as RmaHelper;
use Webkul\RMA\Repositories\{ReasonResolutionRepository, RMAReasonRepository, RMARepository};
use Webkul\RMA\Repositories\RMAAdditionalFieldRepository;
use Webkul\RMA\Repositories\RMAImageRepository;
use Webkul\RMA\Repositories\RMAItemRepository;
use Webkul\RMA\Repositories\RMAMessageRepository;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Shop\Mail\Customer\RMA\CustomerRmaRequestNotification;

class RequestController extends Controller
{
    /**
     * @var string
     */
    public const CANCELED = 'canceled';

    /**
     * @var int
     */
    public const ACTIVE = 1;

    /**
     * @var string
     */
    public const PENDING = 'Pending';

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
        protected ReasonResolutionRepository $reasonResolutionsRepository,
        protected RMAAdditionalFieldRepository $rmaAdditionalFieldRepository,
        protected RmaHelper $rmaHelper,
        protected RMAImageRepository $rmaImagesRepository,
        protected RMAItemRepository $rmaItemsRepository,
        protected RMAMessageRepository $rmaMessagesRepository,
        protected RMAReasonRepository $rmaReasonRepository,
        protected RMARepository $rmaRepository,
    ) {
    }

    /**
     * Display the RMA creation form.
     */
    public function create(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(OrderRMADataGrid::class)->process();
        }

        return view('admin::sales.rma.returns.create');
    }

    /**
     * Store RMA created by admin.
     */
    public function store(): RedirectResponse|JsonResponse
    {
        if (! is_array(request()->input('order_item_id'))) {
            session()->flash('warning', trans('Please select the item'));

            return redirect()->route('admin.sales.rma.create');
        }

        $this->validate(request(), [
            'rma_qty'         => 'required',
            'resolution_type' => 'required',
            'order_status'    => 'required',
            'images.*'        => 'nullable|file|mimetypes:' . core()->getConfigData('sales.rma.setting.allowed_file_extension'),
        ]);

        $requestData = request()->only([
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
            $requestData['package_condition'] = request()->input('package_condition');
        }

        if (request()->input('return_pickup_address')) {
            $requestData['return_pickup_address'] = request()->input('return_pickup_address');
        }

        if (request()->input('return_pickup_time')) {
            $requestData['return_pickup_time'] = request()->input('return_pickup_time');
        }

        $rma = $this->rmaRepository->create([
            'status'            => '',
            'order_id'          => $requestData['order_id'],
            'information'       => $requestData['information'] ?? null,
            'order_status'      => $requestData['order_status'],
            'rma_status'        => self::PENDING,
            'package_condition' => $requestData['package_condition'] ?? '',
        ]);

        $this->storeRelatedData($requestData, $rma);

        return new JsonResponse([
            'messages' => trans('admin::app.rma.sales.rma.create-rma.create-success'),
        ]);
    }

    public function storeRelatedData($data, $rma) {
        $data['order_items'] = [];
        $rmaItemIds = [];

        foreach ($data['order_item_id'] as $key => $orderItemId) {
            $orderItem = $this->orderItemRepository->find($orderItemId);

            if (! empty($orderItem)) {
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

                $data['reason'][] = $this->rmaReasonRepository->findOneWhere(['id' => $data['rma_reason_id'][$key]])?->title;
            }
        }

        $requestData = [
            'message'    => trans('shop::app.rma.mail.customer-conversation.process'),
            'rma_id'     => $rma->id,
            'is_admin'   => 1,
        ];

        $this->rmaMessagesRepository->create($requestData);

        $data['rma_id'] = $rma->id;

        // insert images
        if (! empty($data['images']) && ! empty(implode(',', $data['images']))) {
            foreach ($data['images'] as $itemImg) {
                $this->rmaImagesRepository->create([
                    'rma_id'     => $rma->id,
                    'path'       => $itemImg->getClientOriginalName(),
                ]);
            }

            $this->rmaImagesRepository->uploadImages($data, $rma);
        }

        // Save custom Attributes
        $customAttributes = request('customAttributes') ?? [];

        if ($customAttributes) {

            $customAttributesData = [];

            foreach ($customAttributes as $key => $customAttribute) {
                $customAttributesData = [
                    'rma_id'  => $data['rma_id'],
                    'name'    => $key,
                    'value'   => is_array($customAttribute) ? implode(',', $customAttribute) : $customAttribute,
                ];

                $this->rmaAdditionalFieldRepository->create($customAttributesData);
            }
        }

        $order = $this->orderRepository->findOrFail($rma->order_id);

        $orderItems = $order->items->whereIn('id', $rmaItemIds);

        foreach ($orderItems as $key => $configurableProducts) {
            if ($configurableProducts['type'] == self::CONFIGURABLE) {
                $data['skus'][] = $configurableProducts['child'];
            }
        }

        $data['email'] = $order->customer_email;

        $data['name'] = $order->customer_first_name.' '.$order->customer_last_name;

        unset($data['images']);

        if ($rma->items) {
            try {
                Mail::queue(new CustomerRmaRequestNotification($data));

            } catch (\Exception $e) {
                \Log::error('Error in Sending Email'.$e->getMessage());
            }

            Cookie::has('rmaData') ? setcookie('rmaData', null, -1, '/') : '';

            return response()->json([
                'messages' => trans('admin::app.rma.sales.rma.create-rma.create-success'),
            ]);
        }

        return response()->json([
            'messages' => trans('shop::app.customer.signup-form.failed'),
        ]);
    }

    /**
     * Get order details
     */
    public function getOrderProduct(int $orderId)
    {
        return $this->rmaHelper->getOrderProduct($orderId);
    }

    /**
     * Get reason for resolution.
     */
    public function getResolutionReason(string $resolutionType): ReasonResolution|Collection
    {
        $existResolutions = $this->reasonResolutionsRepository
                                ->where('resolution_type', $resolutionType)
                                ->pluck('rma_reason_id');

        $reasons = $this->rmaReasonRepository
                    ->whereIn('id', $existResolutions)
                    ->where('status', self::ACTIVE)
                    ->get();

        return $reasons;
    }
}
