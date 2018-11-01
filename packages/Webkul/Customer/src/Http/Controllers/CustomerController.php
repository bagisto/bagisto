<?php

namespace Webkul\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Repositories\ProductReviewRepository as ProductReview;
use Webkul\Customer\Models\Customer;
use Auth;

/**
 * Customer controlller for the customer basically for the tasks of customers which will be done after customer authentication.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * CustomerRepository object
     *
     * @var array
    */
    protected $customer;

    /**
     * ProductReviewRepository object
     *
     * @var array
    */
    protected $productReview;

    /**
     * Create a new Repository instance.
     *
     * @param  Webkul\Customer\Repositories\CustomerRepository     $customer
     * @param  Webkul\Product\Repositories\ProductReviewRepository $productReview
     * @return void
    */
    public function __construct(
        CustomerRepository $customer,
        ProductReview $productReview
    )
    {
        $this->middleware('customer');

        $this->_config = request('_config');

        $this->customer = $customer;

        $this->productReview = $productReview;
    }

    /**
     * Taking the customer to profile details page
     *
     * @return View
     */
    public function index()
    {
        $customer = $this->customer->find(auth()->guard('customer')->user()->id);

        return view($this->_config['view'], compact('customer'));
    }

    /**
     * For loading the edit form page.
     *
     * @return View
     */
    public function editIndex()
    {
        $customer = $this->customer->find(auth()->guard('customer')->user()->id);

        return view($this->_config['view'], compact('customer'));
    }

    /**
     * Edit function for editing customer profile.
     *
     * @return Redirect.
     */
    public function edit()
    {

        $id = auth()->guard('customer')->user()->id;

        $this->validate(request(), [

            'first_name' => 'string',
            'last_name' => 'string',
            'gender' => 'required',
            'date_of_birth' => 'date',
            'email' => 'email|unique:customers,email,'.$id,
            'password' => 'confirmed|required_if:oldpassword,!=,null'

        ]);

        $data = collect(request()->input())->except('_token')->toArray();

        if($data['oldpassword'] == null) {
            $data = collect(request()->input())->except(['_token','password','password_confirmation','oldpassword'])->toArray();
            
            if($this->customer->update($data, $id)) {
                Session()->flash('success','Profile Updated Successfully');

                return redirect()->back();
            } else {
                Session()->flash('success','Profile Updated Successfully');

                return redirect()->back();
            }
        } else {
            $data = collect(request()->input())->except(['_token','oldpassword'])->toArray();

            $data['password'] = bcrypt($data['password']);

            if($this->customer->update($data, $id)) {
                Session()->flash('success','Profile Updated Successfully');

                return redirect()->back();
            } else {
                Session()->flash('success','Profile Updated Successfully');

                return redirect()->back();
            }
        }
    }

    /**
     * Load the view for the customer account panel, showing orders in a table.
     *
     * @return Mixed
     */
    public function orders()
    {
        return view($this->_config['view']);
    }

    /**
     * Load the view for the customer account panel, showing wishlist items.
     *
     * @return Mixed
     */
    public function wishlist()
    {
        return view($this->_config['view']);
    }

    /**
     * Load the view for the customer account panel, showing approved reviews.
     *
     * @return Mixed
     */
    public function reviews()
    {
        $reviews = $this->productReview->getCustomerReview();

        return view($this->_config['view'], compact('reviews'));
    }

    /**
     * Load the view for the customer account panel, shows the customer address.
     *
     * @return Mixed
     */
    public function address()
    {
        return view($this->_config['view']);
    }
}
