<?php

namespace Webkul\PreOrder\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Webkul\PreOrder\Repositories\PreOrderItemRepository;
use Webkul\PreOrder\Mail\ProductInStockNotification;

/**
 * PreOrder controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class PreOrderController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * PreOrderItemRepository object
     *
     * @var array
     */
    protected $preOrderItemRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\PreOrder\Repositories\PreOrderItemRepository $preOrderItemRepository
     * @return void
     */
    public function __construct(
        PreOrderItemRepository $preOrderItemRepository
    )
    {
        $this->_config = request('_config');

        $this->preOrderItemRepository = $preOrderItemRepository;
    }

    /**
     * Method to populate the seller order page which will be populated.
     *
     * @return Mixed
     */
    public function index($url)
    {   
        return view($this->_config['view']);
    }

    /**
     * Mass notify customers for in stock preorder products
     *
     * @return response
     */
    public function notifyCustomer()
    {
        $data = request()->all();

        if (! isset($data['massaction-type'])) {
            return redirect()->back();
        }

        $preOrderItemIds = explode(',', $data['indexes']);

        foreach ($preOrderItemIds as $preOrderItemId) {
            $preOrderItem = $this->preOrderItemRepository->find($preOrderItemId);

            if (! $this->preOrderItemRepository->canBeComplete($preOrderItem->order_item))
                continue;

            try {
                Mail::send(new ProductInStockNotification($preOrderItem));

                $this->preOrderItemRepository->update([
                    'email_sent' => 1
                ], $preOrderItem->id);
            } catch (\Exception $e) {

            }
        }

        session()->flash('success', trans('preorder::app.admin.preorders.mass-notify-success'));

        return redirect()->route($this->_config['redirect']);
    }
}