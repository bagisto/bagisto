<?php

namespace Webkul\CustomerDocument\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\CustomerDocument\Contracts\CustomerDocument as CustomerDocumentContract;

class CustomerDocument extends Model implements CustomerDocumentContract
{
    protected $table = 'customer_documents';

    protected $fillable = ['name', 'path', 'customer_id', 'description'];
}