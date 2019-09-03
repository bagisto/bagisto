<?php

namespace Webkul\CustomerDocument\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\CustomerDocument\Repositories\CustomerDocumentRepository;
use Webkul\CustomerDocument\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

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
        return view($this->_config['view']);
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
        $excludedExtensions = core()->getConfigData('customer.settings.documents.allowed_extensions');
        $maxSize = core()->getConfigData('customer.settings.documents.size');

        if ($excludedExtensions != '') {
            $valid_extension = explode(',', $excludedExtensions);
        } else {
            $valid_extension = [];
        }

        $originalSize = filesize(request()->file('file')) / 1000;

        if ($maxSize != null) {
            $maxSize = (float) $maxSize * 1000;
        } else {
            $maxSize = 5 * 1024;
        }

        if (! ($originalSize <= $maxSize) || $originalSize == 0) {
            session()->flash('error', trans('customerdocument::app.admin.customers.size-error'));

            return redirect()->back();
        }

        if (! empty($valid_extension) && (! in_array(request()->file('file')->getClientOriginalExtension(), $valid_extension))) {
            session()->flash('error', trans('customerdocument::app.admin.customers.upload-error'));

            return redirect()->back();
        } else {
            try {
                $data = request()->all();

                if (request()->hasFile('file')) {
                    $dir = 'customer';
                    $document['path'] = request()->file('file')->store($dir);
                }

                if (isset($data['customer_id'])) {
                    $document['customer_id'] = $data['customer_id'];
                } else {
                    $document['customer_id'] = 0;
                }

                if (isset($data['status'])) {
                    $document['status'] = $data['status'];
                } else {
                    $document['status'] = 1;
                }

                $document['name'] = $data['name'];
                $document['description'] = $data['description'];
                $document['type'] = $data['type'];

                $this->customerDocument->create($document);

                session()->flash('success', trans('customerdocument::app.admin.customers.upload-success'));

                if (isset($data['customer_id'])) {
                    return redirect()->back();
                }

                return redirect()->route($this->_config['redirect']);
            } catch (\Exception $e) {
                session()->flash('error', $e);

                return redirect()->back();
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $document = $this->customerDocument->findOrFail($id);

        return view($this->_config['view'], compact('document'));
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $excludedExtensions = core()->getConfigData('customer.settings.documents.allowed_extensions');
        $maxSize = core()->getConfigData('customer.settings.documents.size');

        if ($excludedExtensions != '') {
            $valid_extension = explode(',', $excludedExtensions);
        } else {
            $valid_extension = [];
        }

        $originalSize = filesize(request()->file('file')) / 1000;

        if ($maxSize != null) {
            $maxSize = (float) $maxSize * 1000;
        } else {
            $maxSize = 5 * 1024;
        }

        if (! ($originalSize <= $maxSize)) {
            session()->flash('error', trans('customerdocument::app.admin.customers.size-error'));

            return redirect()->back();
        }

        if (! empty($valid_extension) && (! in_array(request()->file('file')->getClientOriginalExtension(), $valid_extension))) {
            session()->flash('error', trans('customerdocument::app.admin.customers.upload-error'));

            return redirect()->back();
        } else {
            try {
                $data = request()->all();

                $uploaDocument = $this->customerDocument->findOrfail($id);

                if (request()->hasFile('file')) {
                    $dir = 'customer';
                    $document['path'] = request()->file('file')->store($dir);
                    Storage::delete($uploaDocument['path']);
                }

                $document['name'] = $data['name'];
                $document['description'] = $data['description'];
                $document['type'] = $data['type'];
                $document['status'] = $data['status'];

                $this->customerDocument->update($document, $id);

                session()->flash('success', trans('customerdocument::app.admin.customers.upload-success'));

                return redirect()->route($this->_config['redirect']);
            } catch (\Exception $e) {
                session()->flash('error', $e);

                return redirect()->back();
            }
        }
    }

    /**
     * Remove the specified resource from storage..
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $document = $this->customerDocument->findOrfail($id);

        try {
            $this->customerDocument->delete($id);

            Storage::delete($document['path']);

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Document']));

            return response()->json(['message' => true], 200);
        } catch (\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Document']));
        }

        return response()->json(['message' => false], 400);
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

        $extension = explode('.', $document['path']);

        $name = $document['name'].'.'.end($extension);

        return Storage::download($document['path'], $name);
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