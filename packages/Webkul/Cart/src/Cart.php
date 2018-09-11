<?php

namespace Webkul\Cart;

use Carbon\Carbon;

use Webkul\Cart\Models\Cart as Cartmodel;
use Webkul\Cart\Models\CartProduct;

use Webkul\Core\Models\Channel as ChannelModel;
use Webkul\Core\Models\Locale as LocaleModel;
use Webkul\Core\Models\TaxCategory as TaxCategory;
use Webkul\Core\Models\TaxRate as TaxRate;

use Webkul\Customer\Models\Customer;
use Cookie;

class Cart {
    public function getCart($id) {
        dd('juice', $id);
    }

    public function getProducts($id) {
        dd('juice 1', $id);
    }
}