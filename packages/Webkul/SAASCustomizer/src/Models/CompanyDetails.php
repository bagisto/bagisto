<?php

namespace Webkul\SAASCustomizer\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\Product;
use Webkul\Core\Models\Locale;

class CompanyDetails extends Model
{
    use Notifiable;

    protected $table = 'company_personal_details';

    protected $fillable = ['first_name', 'last_name', 'email', 'skype', 'extra_info', 'company_id'];
}