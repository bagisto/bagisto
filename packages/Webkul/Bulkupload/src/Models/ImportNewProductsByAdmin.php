<?php

namespace Webkul\Bulkupload\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Bulkupload\Contracts\ImportNewProductsByAdmin as ImportNewProductsByAdminContract;

class ImportNewProductsByAdmin extends Model implements ImportNewProductsByAdminContract
{
    //
    protected $table = "import_new_products_by_admin";

    protected $guarded = array();
}
