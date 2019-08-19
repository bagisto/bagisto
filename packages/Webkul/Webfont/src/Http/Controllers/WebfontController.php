<?php

namespace Webkul\Webfont\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Webkul\Webfont\Repositories\WebfontRepository as Webfont;
use GuzzleHttp;

/**
 * Webfont Controller
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class WebfontController extends Controller
{
    /**
     * Hold webfont repository instance
     */
    protected $webfont;

    public function __construct(Webfont $webfont)
    {
        $this->middleware('auth:admin');

        $this->webfont = $webfont;
    }

    public function index()
    {
        return view('webfont::admin-index');
    }

    public function add()
    {
        $client = new GuzzleHttp\Client();

        if (core()->getConfigData('general.design.webfont.webfont')) {
            $res = $client->request('GET', 'https://www.googleapis.com/webfonts/v1/webfonts?key='.core()->getConfigData('general.design.webfont.webfont'));

            if ($res->getStatusCode() == 200) {
                $result =  (string) $res->getBody()->getContents();

                return view('webfont::add-font')->with('fonts', json_decode($result)->items);
            } else {
                session()->flash('error', trans('webfont::app.cannot-fetch'));

                return redirect()->route('admin.cms.webfont');
            }
        } else {
            session()->flash('error', trans('webfont::app.set-api-key'));

            return redirect()->route('admin.cms.webfont');
        }
    }

    public function store()
    {
        $fonts = request()->input('fonts');

        $fonts = json_decode($fonts);

        foreach($fonts as $font) {
            $this->webfont->create(['font' => $font]);
        }

        session()->flash('success', trans('webfont::app.create-success'));

        return redirect()->route('admin.cms.webfont');
    }

    public function activate($id)
    {
        $font = $this->webfont->findWhere([
            'activated' => 1
        ]);

        if (count($font)) {
            $font = $font->first();

            $font->update([
                'activated' => 0
            ]);

            $font = $this->webfont->findOrFail($id);

            $font->update([
                'activated' => 1
            ]);
        } else {
            $font = $this->webfont->findOrFail($id);

            $font->update([
                'activated' => 1
            ]);
        }

        session()->flash('success', trans('webfont::app.active-success'));

        return redirect()->route('admin.cms.webfont');
    }

    public function remove($id)
    {
        $font = $this->webfont->findOrFail($id);

        if($font->delete()) {
            session()->flash('success', trans('webfont::app.delete-success'));

            return response()->json(['message' => true], 200);
        } else {
            session()->flash('error', trans('webfont::app.delete-fail'));
        }
    }

    public function fetchAndSync()
    {
        $client = new GuzzleHttp\Client();

        return view('webfont::add-font');
    }
}