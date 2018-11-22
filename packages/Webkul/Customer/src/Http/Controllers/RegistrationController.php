<?php

namespace Webkul\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Webkul\Customer\Mail\VerificationEmail;
use Illuminate\Routing\Controller;
use Webkul\Customer\Repositories\CustomerRepository;

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
            'agreement' => 'required'
        ]);

        $data = request()->input();

        $data['password'] = bcrypt($data['password']);

        $data['channel_id'] = core()->getCurrentChannel()->id;

        $data['is_verified'] = 0;

        if ($this->customer->create($data)) {

            $verificationData['email'] = $data['email'];

            session()->flash('success', 'Account Created Successfully');

            $attempt = auth()->guard('customer')->attempt(request(['email', 'password']));

            if($attempt) {
                try {
                    Mail::send(new VerificationEmail($verificationData));
                }catch(\Exception $e) {
                    session()->flash('success', 'Account Created Successfully, But Verification Email Is Not Sent.');

                    return redirect()->route($this->_config['redirect']);
                }

                return redirect()->route($this->_config['redirect']);
            } else {
                return redirect()->back();
            }
        } else {
            session()->flash('error', 'Cannot Create Your Account');

            return redirect()->back();
        }
    }

    /**
     * Method to verify account
     *
     */
    public function verifyAccount($email)
    {
        $customer = $this->customer->findOneByField('email', $email);

        if($customer) {
            if($customer->is_verified == 1) {
                session()->flash('error', 'Your Account is already verified');
            } else {
                $data['is_verified'] = 1;
                $this->customer->update($data,$customer->id);
                session()->flash('info', 'Your Account has been verified');
            }
        } else {
            session()->flash('error', 'You dont have account with us');
        }

        return redirect()->back();
    }
}