<?php

namespace Webkul\CustomerDocument\Http\Controllers\Shop;

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
        $documents = $this->customerDocument->getDocuments(auth()->guard('customer')->user()->id);

        $productDocument = $marketingDocument = [];

        foreach($documents as $document) {
            if ($document->type == 'marketing') {
                $marketingDocument[] = $document;
            } else if ($document->type == 'product') {
                $productDocument[] = $document;
            }
        }

        return view($this->_config['view'], compact('productDocument', 'marketingDocument'));
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
}