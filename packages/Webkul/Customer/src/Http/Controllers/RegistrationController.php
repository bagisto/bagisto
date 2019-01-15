<?php

namespace Webkul\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Webkul\Customer\Mail\VerificationEmail;
use Illuminate\Routing\Controller;
use Webkul\Customer\Repositories\CustomerRepository;
use Cookie;

/**
 * Registration controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;
    protected $customer;

    /**
     * @param CustomerRepository object $customer
     */
    public function __construct(CustomerRepository $customer)
    {
        $this->_config = request('_config');
        $this->customer = $customer;
    }

    /**
     * Opens up the user's sign up form.
     *
     * @return view
     */
    public function show()
    {
        return view($this->_config['view']);
    }

    /**
     * Method to store user's sign up form data to DB.
     *
     * @return Mixed
     */
    public function create(Request $request)
    {
        $request->validate([
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'email' => 'email|required|unique:customers,email',
            'password' => 'confirmed|min:6|required',
            // 'agreement' => 'required'
        ]);

        $data = request()->input();

        $data['password'] = bcrypt($data['password']);

        $data['channel_id'] = core()->getCurrentChannel()->id;

        $data['is_verified'] = 1;

        $data['customer_group_id'] = 1;

        $verificationData['email'] = $data['email'];
        $verificationData['token'] = md5(uniqid(rand(), true));
        $data['token'] = $verificationData['token'];

        Event::fire('customer.registration.before');

        $customer = $this->customer->create($data);

        Event::fire('customer.registration.after', $customer);

        if ($customer) {
            try {
                session()->flash('success', trans('shop::app.customer.signup-form.success'));

                Mail::send(new VerificationEmail($verificationData));
            } catch(\Exception $e) {
                session()->flash('info', trans('shop::app.customer.signup-form.success-verify-email-not-sent'));

                return redirect()->route($this->_config['redirect']);
            }

            return redirect()->route($this->_config['redirect']);
        } else {
            session()->flash('error', trans('shop::app.customer.signup-form.failed'));

            return redirect()->back();
        }
    }

    /**
     * Method to verify account
     *
     * @param string $token
     */
    public function verifyAccount($token)
    {
        $customer = $this->customer->findOneByField('token', $token);

        if($customer) {
            $customer->update(['is_verified' => 1, 'token' => 'NULL']);

            session()->flash('success', trans('shop::app.customer.signup-form.verified'));
        } else {
            session()->flash('warning', trans('shop::app.customer.signup-form.verify-failed'));
        }

        return redirect()->route('customer.session.index');
    }

    public function resendVerificationEmail($email)
    {
        $verificationData['email'] = $email;
        $verificationData['token'] = md5(uniqid(rand(), true));

        $customer = $this->customer->findOneByField('email', $email);

        $this->customer->update(['token' => $verificationData['token']], $customer->id);

        try {
            Mail::send(new VerificationEmail($verificationData));

            if(Cookie::has('enable-resend')) {
                \Cookie::queue(\Cookie::forget('enable-resend'));
            }

            if(Cookie::has('email-for-resend')) {
                \Cookie::queue(\Cookie::forget('email-for-resend'));
            }
        } catch(\Exception $e) {
            session()->flash('success', trans('shop::app.customer.signup-form.verification-not-sent'));

            return redirect()->back();
        }
        session()->flash('success', trans('shop::app.customer.signup-form.verification-sent'));

        return redirect()->back();
    }
}