<?php

namespace Webkul\Admin\Http\Controllers\Customers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Webkul\Admin\DataGrids\Customers\GDPRDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Mail\Customer\Gdpr\UpdateRequestMail;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\GDPR\Repositories\GDPRDataRequestRepository;
use Webkul\GDPR\Repositories\GDPRRepository;

class GDPRController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected GDPRRepository $gdprRepository,
        protected GDPRDataRequestRepository $gdprDataRequestRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(GDPRDataGrid::class)->process();
        }

        return view('admin::customers.gdpr.index');
    }

    /**
     * Method to show the form for updating a new Data Request.
     */
    public function edit()
    {
        $request = $this->gdprDataRequestRepository->find(request()->id);

        return new JsonResponse([
            'data' => $request,
        ], 200);
    }

    /**
     * Method to update the Data Request information.
     */
    public function update()
    {
        $request = $this->gdprDataRequestRepository->find(request()->id);

        $customer = $this->customerRepository->find($request->customer_id);

        $params = request()->all() + [
            'customer_id'   => $request->customer_id,
            'customer_name' => $customer->first_name.' '.$customer->last_name,
            'email'         => $request->email,
        ];

        $result = $request->update(request()->all());

        if ($result) {
            try {
                Mail::queue(new UpdateRequestMail($params));

                return response()->json([
                    'message' => trans('admin::app.customers.gdpr.index.update-success'),
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => trans('admin::app.customers.gdpr.index.update-success-unsent-email'),
                ], 404);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(int $id)
    {
        try {
            $this->gdprDataRequestRepository->delete($id);

            return new JsonResponse([
                'message' => trans('admin::app.customers.gdpr.index.delete-success'),
            ], 200);
        } catch (\Exception $e) {
        }

        return new JsonResponse([
            'message' => trans('admin::app.customers.gdpr.index.attribute-reason-error'),
        ], 500);
    }
}
