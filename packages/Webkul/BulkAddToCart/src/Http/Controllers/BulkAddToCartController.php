<?php

namespace Webkul\BulkAddToCart\Http\Controllers;

use Webkul\Admin\Imports\DataGridImport;
use Webkul\Product\Repositories\ProductRepository as Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Event;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Storage;
use Excel;
use Cart;

/**
 * BulkAddToCart controlller
 *
 * @author    Rahul Shukla <rahulshukla.symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class BulkAddToCartController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * ProductRepository object
     *
     * @var array
     */
    protected $product;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository  $product
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;

        $this->_config = request('_config');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $valid_extension = ['xlsx', 'csv', 'xls', 'ods'];

        if (! in_array(request()->file('file')->getClientOriginalExtension(), $valid_extension)) {
            session()->flash('error', trans('bulkaddtocart::app.products.upload-error'));

            return redirect()->back();
        } else {
            try {
                $excelData = (new DataGridImport)->toArray(request()->file('file'));
                $cart = [];

                foreach ($excelData as $data) {
                    foreach ($data as $column => $uploadData) {
                        $validator = Validator::make($uploadData, [
                            'sku' => 'required',
                            'quantity' => 'required|numeric|min:1',
                        ]);

                        $product = $this->product->findOneWhere([
                            'sku' => $uploadData['sku'],
                        ]);

                        if ($product) {
                            $canAdd = $product->haveSufficientQuantity($uploadData['quantity']);

                            if (! $canAdd) {
                                $sufficientQuantity[] = $column + 1;
                            } else if($product->type == 'simple' && $uploadData['quantity'] > 0) {
                                $cart['product'] = (string)$product->id;
                                $cart['quantity'] = (string)$uploadData['quantity'];
                                $cart['is_configurable'] = 'false';

                                Event::fire('checkout.cart.add.before', $cart['product']);

                                $result = Cart::add($cart['product'], $cart);

                                Event::fire('checkout.cart.add.after', $result);

                                Cart::collectTotals();
                            }
                        } else {
                            $skuError[] = $column + 1;
                        }

                        if ($validator->fails()) {
                            $failedRules[$column+1] = $validator->errors();
                        }
                    }
                }

                if (isset($failedRules)) {
                    foreach ($failedRules as $coulmn => $fail) {
                        if ($fail->first('sku')) {
                            $errorMsg[$coulmn] = $fail->first('sku');
                        } else if ($fail->first('quantity')) {
                            $errorMsg[$coulmn] = $fail->first('quantity');
                        }
                    }

                    foreach ($errorMsg as $key => $msg) {
                        $msg = str_replace(".", "", $msg);
                        $message[] = $msg. ' at Row '  .$key . '.';
                    }

                    $finalMsg[] = implode(" ", $message);
                }
                if (isset($skuError)) {
                    $errorRows = implode(",", $skuError);
                    $finalMsg[] = trans('bulkaddtocart::app.products.sku-error') . ' ' . $errorRows . '.';
                }
                if (isset($sufficientQuantity)) {
                    $errorRows = implode(",", $sufficientQuantity);
                    $finalMsg[] = trans('bulkaddtocart::app.products.quantity-error') . ' ' . $errorRows . '.';
                }
                if (isset($finalMsg)) {
                    $finalErrorMsg = implode(" ", $finalMsg);

                    session()->flash('error', $finalErrorMsg);

                    return redirect()->back();
                } else {
                    session()->flash('success', trans('bulkaddtocart::app.products.upload-sucess'));

                    return redirect()->route($this->_config['redirect']);
                }
            } catch (\Exception $e) {
                $failure = new Failure(1, 'rows', [0 => trans('bulkaddtocart::app.products.enough-row-error')]);

                session()->flash('error', $failure->errors()[0]);

                return redirect()->back();
            }
        }
    }

    /**
     * Download Sample
     *
     * @return \Illuminate\Http\Response
     */
    public function downLoadSample()
    {
        return Storage::download('sample/sample.xls');
    }
}