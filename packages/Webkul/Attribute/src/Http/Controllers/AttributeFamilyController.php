<?php

namespace Webkul\Attribute\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Attribute\Repositories\AttributeRepository as Attribute;


/**
 * Catalog family controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AttributeFamilyController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;
    
    /**
     * AttributeFamilyRepository object
     *
     * @var array
     */
    protected $attributeFamily;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeFamilyRepository  $attributeFamily
     * @return void
     */
    public function __construct(AttributeFamily $attributeFamily)
    {
        $this->attributeFamily = $attributeFamily;

        $this->_config = request('_config');
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
     * @param  Webkul\Attribute\Repositories\AttributeRepository  $attribute
     * @return \Illuminate\Http\Response
     */
    public function create(Attribute $attribute)
    {
        $attributeFamily = $this->attributeFamily->with(['attribute_groups.custom_attributes'])->findOneByField('code', 'default');
        
        $custom_attributes = $attribute->all(['id', 'code', 'admin_name', 'type']);

        return view($this->_config['view'], compact('custom_attributes', 'attributeFamily'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'code' => ['required', 'unique:attribute_families,code', new \Webkul\Core\Contracts\Validations\Code],
            'name' => 'required'
        ]);

        $this->attributeFamily->create(request()->all());

        session()->flash('success', 'Family created successfully.');

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository  $attribute
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Attribute $attribute, $id)
    {
        $attributeFamily = $this->attributeFamily->find($id, ['*'], ['attribute_groups.custom_attributes']);

        $custom_attributes = $attribute->all(['id', 'code', 'admin_name', 'type']);

        return view($this->_config['view'], compact('attributeFamily', 'custom_attributes'));
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
        $this->validate(request(), [
            'code' => ['required', 'unique:attribute_families,code,' . $id, new \Webkul\Core\Contracts\Validations\Code],
            'name' => 'required'
        ]);
        

        $this->attributeFamily->update(request()->all(), $id);

        session()->flash('success', 'Family updated successfully.');

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}