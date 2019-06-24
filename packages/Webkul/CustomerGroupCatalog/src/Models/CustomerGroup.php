<?php
namespace Webkul\CustomerGroupCatalog\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\SAASCustomizer\Models\Customer\CustomerGroup as BaseCustomerGroup;
use Webkul\Product\Models\ProductProxy;
use Webkul\Category\Models\CategoryProxy;

class CustomerGroup extends BaseCustomerGroup
{
    /**
     * The products that belong to the customer group.
     */
    public function products()
    {
        return $this->belongsToMany(ProductProxy::modelClass(), 'customer_group_products');
    }

    /**
     * The categories that belong to the customer group.
     */
    public function categories()
    {
        return $this->belongsToMany(CategoryProxy::modelClass(), 'customer_group_categories');
    }
}