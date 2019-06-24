<?php

namespace Webkul\CustomerDocument\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\CustomerDocument\Repositories\CustomerDocumentRepository;
use Webkul\CustomerDocument\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Validator;

/**
 * Document controlller
 *
 * @author    Rahul Shukla <rahulshukla.symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class DocumentController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

     /**
     * CustomerDocumentRepository object
     *
     * @var array
     */
    protected $customerDocument;

    /**
     * Create a new controller instance.
     *
     * @param \Webkul\Customer\Repositories\CustomerDocumentRepository $customerDocument
     */
    public function __construct(CustomerDocumentRepository $customerDocument)
    {
        $this->_config = request('_config');

        $this->customerDocument = $customerDocument;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = $this->customerDocument->findWhere(['customer_id' => auth()->guard('customer')->user()->id]);

        return view($this->_config['view'], compact('documents'));
    }

    /**
     * upload document
     *
     * @return \Illuminate\Http\Response
    */
    public function upload()
    {
        $availableMimes = core()->getConfigData('customer.settings.documents.allowed_extensions');
        $maxSize = core()->getConfigData('customer.settings.documents.size');

        if ($maxSize == null) {
            $maxSize = 5 * 1024;
        } else {
            $maxSize = $maxSize * 1024;
        }

        $maxSize = 10;

        $customerId = request()->input('customer_id');

        if (strlen($availableMimes)) {
            $validator = Validator::make(request()->input(), [
                'file' => 'required|file|mimes:'.$availableMimes.'|size:'.$maxSize
            ]);
        } else {
            $validator = Validator::make(request()->input(), [
                'file' => 'required|file|size:'.$maxSize
            ]);
        }

        if ($validator->fails()) {
            session()->flash('error', $validator->errors());
            return redirect()->back();
        }

        try {
            $data = request()->all();

            if (request()->hasFile('file')) {
                $dir = 'customer';
                $document['path'] = request()->file('file')->store($dir);
            }

            $document['customer_id'] = $data['customer_id'];
            $document['name'] = $data['name'];
            $document['description'] = $data['description'];

            $this->customerDocument->create($document);

            session()->flash('success', trans('customerdocument::app.admin.customers.upload-success'));

            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('error', $e);

            return redirect()->back();
        }
    }

    /**
     * download the file for the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $document = $this->customerDocument->findOrfail($id);

        return Storage::download($document['path'], $document['name']);
    }

    /**
     * Remove the specified resource from storage..
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $document = $this->customerDocument->findOrfail($id);

        $this->customerDocument->delete($id);

        Storage::delete($document['path']);

        session()->flash('success', trans('customerdocument::app.admin.customers.delete-success'));

        return redirect()->back();
    }
}