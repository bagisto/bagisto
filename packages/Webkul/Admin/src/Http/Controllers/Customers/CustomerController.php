<?php

namespace Webkul\Admin\Http\Controllers\Customers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Webkul\Admin\DataGrids\Customers\CustomerDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\Admin\Mail\Customer\NewCustomerNotification;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Customer\Repositories\CustomerNoteRepository;
use Webkul\Customer\Repositories\CustomerRepository;

class CustomerController extends Controller
{
    /**
     * Static pagination count.
     *
     * @var int
     */
    public const COUNT = 10;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected CustomerGroupRepository $customerGroupRepository,
        protected CustomerNoteRepository $customerNoteRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(CustomerDataGrid::class)->toJson();
        }

        $groups = $this->customerGroupRepository->findWhere([['code', '<>', 'guest']]);

        return view('admin::customers.customers.index', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'first_name'    => 'string|required',
            'last_name'     => 'string|required',
            'gender'        => 'required',
            'email'         => 'required|unique:customers,email',
            'date_of_birth' => 'date|before:today',
            'phone'         => 'unique:customers,phone',
        ]);

        $password = rand(100000, 10000000);

        Event::dispatch('customer.registration.before');

        $data = array_merge(request()->only([
            'first_name',
            'last_name',
            'gender',
            'email',
            'date_of_birth',
            'phone',
            'customer_group_id',
        ]), [
            'password'    => bcrypt($password),
            'is_verified' => 1,
        ]);

        $customer = $this->customerRepository->create($data);

        if (core()->getConfigData('emails.general.notifications.emails.general.notifications.customer')) {
            try {
                Mail::queue(new NewCustomerNotification($customer, $password));
            } catch (\Exception $e) {
                report($e);
            }
        }

        return new JsonResponse([
            'message' => trans('admin::app.customers.customers.index.create.create-success'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(int $id)
    {
        $this->validate(request(), [
            'first_name'    => 'string|required',
            'last_name'     => 'string|required',
            'gender'        => 'required',
            'email'         => 'required|unique:customers,email,'.$id,
            'date_of_birth' => 'date|before:today',
            'phone'         => 'unique:customers,phone,'.$id,
        ]);

        Event::dispatch('customer.update.before', $id);

        $customer = $this->customerRepository->update(request()->only([
            'first_name',
            'last_name',
            'gender',
            'email',
            'date_of_birth',
            'phone',
            'customer_group_id',
            'status',
            'is_suspended',
        ]), $id);

        Event::dispatch('customer.update.after', $customer);

        return new JsonResponse([
            'message' => trans('admin::app.customers.customers.update-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $customer = $this->customerRepository->findorFail($id);

        if (! $customer) {
            return response()->json(['message' => trans('admin::app.customers.customers.delete-failed')], 400);
        }

        if (! $this->customerRepository->haveActiveOrders($customer)) {

            $this->customerRepository->delete($id);

            session()->flash('success', trans('admin::app.customers.customers.delete-success'));

            return redirect()->route('admin.customers.customers.index');
        }

        session()->flash('error', trans('admin::app.customers.customers.view.order-pending'));

        return redirect()->route('admin.customers.customers.index');
    }

    /**
     * Login as customer
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginAsCustomer(int $id)
    {
        $customer = $this->customerRepository->findOrFail($id);

        auth()->guard('customer')->login($customer);

        session()->flash('success', trans('admin::app.customers.customers.index.login-message', ['customer_name' => $customer->name]));

        return redirect(route('shop.customers.account.profile.index'));
    }

    /**
     * To store the response of the note.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeNotes(int $id)
    {
        $this->validate(request(), [
            'note' => 'string|required',
        ]);

        Event::dispatch('customer.note.create.before', $id);

        $customerNote = $this->customerNoteRepository->create([
            'customer_id'       => $id,
            'note'              => request()->input('note'),
            'customer_notified' => request()->input('customer_notified', 0),
        ]);

        Event::dispatch('customer.note.create.after', $customerNote);

        session()->flash('success', trans('admin::app.customers.customers.view.note-created-success'));

        return redirect()->route('admin.customers.customers.view', $id);
    }

    /**
     * View all details of customer.
     */
    public function show(int $id)
    {
        $customer = $this->customerRepository->with([
            'orders',
            'orders.addresses',
            'invoices',
            'reviews',
            'notes',
            'addresses',
        ])->findOrFail($id);

        if (request()->ajax()) {
            return new JsonResponse([
                'customer' => $customer,
                'groups'   => $customer->group,
            ]);
        }

        $groups = $this->customerGroupRepository->findWhere([['code', '<>', 'guest']]);

        return view('admin::customers.customers.view', compact('customer', 'groups'));
    }

    /**
     * Result of search customer.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $customers = $this->customerRepository->scopeQuery(function ($query) {
            return $query->where('email', 'like', '%'.urldecode(request()->input('query')).'%')
                ->orWhere(DB::raw('CONCAT('.DB::getTablePrefix().'first_name, " ", '.DB::getTablePrefix().'last_name)'), 'like', '%'.urldecode(request()->input('query')).'%')
                ->orderBy('created_at', 'desc');
        })->paginate(self::COUNT);

        return response()->json($customers);
    }

    /**
     * To mass update the customer.
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest): JsonResponse
    {
        $selectedCustomerIds = $massUpdateRequest->input('indices');

        foreach ($selectedCustomerIds as $customerId) {
            Event::dispatch('customer.update.before', $customerId);

            $customer = $this->customerRepository->update([
                'status' => $massUpdateRequest->input('value'),
            ], $customerId);

            Event::dispatch('customer.update.after', $customer);
        }

        return new JsonResponse([
            'message' => trans('admin::app.customers.customers.index.datagrid.update-success'),
        ]);
    }

    /**
     * To mass delete the customer.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $customers = $this->customerRepository->findWhereIn('id', $massDestroyRequest->input('indices'));

        try {
            /**
             * Ensure that customers do not have any active orders before performing deletion.
             */
            foreach ($customers as $customer) {
                if ($this->customerRepository->haveActiveOrders($customer)) {
                    throw new \Exception(trans('admin::app.customers.customers.index.datagrid.order-pending'));
                }
            }

            /**
             * After ensuring that they have no active orders delete the corresponding customer.
             */
            foreach ($customers as $customer) {
                Event::dispatch('customer.delete.before', $customer);

                $this->customerRepository->delete($customer->id);

                Event::dispatch('customer.delete.after', $customer);
            }

            return new JsonResponse([
                'message' => trans('admin::app.customers.customers.index.datagrid.delete-success'),
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
