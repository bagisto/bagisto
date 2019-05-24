<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Shop\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Core\Repositories\SliderRepository;
use Webkul\Discount\Repositories\CartRuleRepository as CartRule;
use Carbon\Carbon;
/**
 * Home page controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
 class HomeController extends Controller
{
    protected $_config;

    protected $sliderRepository;

    protected $current_channel;

    protected $cartRule;

    public function __construct(SliderRepository $sliderRepository, CartRule $cartRule)
    {
        $this->_config = request('_config');

        $this->sliderRepository = $sliderRepository;

        $this->cartRule = $cartRule;
    }

    /**
     * loads the home page for the storefront
     */
    public function index()
    {
        $currentChannel = core()->getCurrentChannel();

        $rules = $this->cartRule->findWhere(['status' => 1, 'end_other_rules' => 1]);

        $suitableRules = array();
        if ($rules->count() == 0) {
            $rules = $this->cartRule->findWhere(['status' => 1]);
        } else {
            foreach ($rules as $rule) {
                foreach($rule->channels as $channel) {
                    if ($channel->channel_id == $currentChannel->id) {
                        if (auth()->guard('customer')->check()) {
                            foreach ($rule->customer_groups as $customerGroup) {
                                if (auth()->guard('customer')->user()->customer_group_id == $customerGroup->customer_group_id)
                                    array_push($suitableRules, $rule);
                            }
                        }
                    }
                }
            }

            // suitable rules will now be checked against the
            if (count($suitableRules) == 1) {
                $rule = reset($suitableRules);
            } else if (count($suitableRules) > 1) {
                $priorities = array();

                $leastPriority = 999999999999;
                foreach ($suitableRules as $rule) {
                    if ($leastPriority > $rule->priority) {
                        $leastPriority = $rule->priority;
                    }
                }

                $leastId = 999999999999;
                foreach ($suitableRules as $rule) {
                    if ($rule->id < $leastId) {
                        $leastId = $rule->id;
                    }
                }

                if ($leastId != 999999999999) {
                    $rule = $this->cartRule->find($leastId);
                }
            }
            // foreach($rules as $rule) {
            //     foreach($rule->channels as $channel) {

            //     }
            //     $starts_from = Carbon::parse($rule->starts_from);
            //     $ends_till = Carbon::parse($rule->ends_till);

            //     $now = Carbon::now();

            //     if ($now->greaterThanOrEqualTo($starts_from) && $now->lessThanOrEqualTo($ends_till)) {
            //         dd('date time matched');
            //     }
            // }
        }

        $sliderData = $this->sliderRepository->findByField('channel_id', $currentChannel->id)->toArray();

        return view($this->_config['view'], compact('sliderData'));
    }

    /**
     * loads the home page for the storefront
     */
    public function notFound()
    {
        abort(404);
    }
}