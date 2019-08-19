<?php

namespace Webkul\SAASCustomizer\Http\Controllers;

use Webkul\SAASCustomizer\Http\Controllers\Controller;
use Webkul\SAASCustomizer\Helpers\DataPurger;
use Event;
use Company;

/**
 * Purge controller
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class PurgeController extends Controller
{
    protected $dataSeed;

    public function __construct(DataPurger $dataSeed)
    {
        $this->dataSeed = $dataSeed;
        $this->_config = request('_config');
    }

    public function seedDatabase()
    {
        $this->dataSeed->prepareLocaleData();
        $this->dataSeed->prepareCategoryData(); // translation table not getting populated
        $this->dataSeed->prepareInventoryData();
        $this->dataSeed->prepareCurrencyData();
        $this->dataSeed->prepareChannelData();

        // need to get executed only first time
        if (Company::count() == 1)
            $this->dataSeed->prepareCountryStateData();

        $this->dataSeed->prepareCustomerGroupData();
        $this->dataSeed->prepareAttributeData();
        $this->dataSeed->prepareAttributeFamilyData();
        $this->dataSeed->prepareAttributeGroupData();

        Event::fire('new.company.registered');

        $this->dataSeed->setInstallationCompleteParam();

        session()->flash('success', trans('saas::app.status.store-created'));

        return redirect()->route('shop.home.index');
    }
}