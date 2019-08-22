<?php

namespace Webkul\API\Http\Controllers\Shop;

use Illuminate\Support\Facades\Event;
use Webkul\Customer\Repositories\CustomerRepository;

/**
 * Customer controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CustomerController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Repository object
     *
     * @var array
     */
    protected $customerRepository;

    /**
     * @param CustomerRepository object $customer
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->_config = request('_config');

        $this->customerRepository = $customerRepository;
    }

    /**
     * Method to store user's sign up form data to DB.
     *
     * @return Mixed
     */
    public function create()
    {
        request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email|required|unique:customers,email',
            'password' => 'confirmed|min:6|required'
        ]);

        $data = request()->input();

        $data = array_merge($data, [
                'password' => bcrypt($data['password']),
                'channel_id' => core()->getCurrentChannel()->id,
                'is_verified' => 1,
                'customer_group_id' => 1
            ]);

        Event::fire('customer.registration.before');

        $customer = $this->customerRepository->create($data);

        Event::fire('customer.registration.after', $customer);

        return response()->json([
                'message' => 'Your account has been created successfully.'
            ]);
    }
}