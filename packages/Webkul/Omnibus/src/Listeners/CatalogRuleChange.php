<?php

namespace Webkul\Omnibus\Listeners;

use Webkul\CatalogRule\Contracts\CatalogRule;

class CatalogRuleChange
{
    /**
     * @param  CatalogRule  $catalogRule
     */
    public function afterSave($catalogRule)
    {
        if (! core()->getConfigData('catalog.products.omnibus.is_enabled')) {
            return;
        }

        // TODO: Currently a placeholder for complex global promo tracking.
    }
}
