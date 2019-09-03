<?php

namespace Webkul\SAASCustomizer\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

use Webkul\Product\Models\Product;
use Webkul\Core\Models\Locale;
use Webkul\SAASCustomizer\Models\CompanyDetails;
use Webkul\SAASCustomizer\Models\SuperAdmin;

class Company extends Model
{
    use Notifiable;

    protected $table = 'companies';

    protected $fillable = ['name', 'code', 'username','description', 'country', 'state', 'city', 'address1', 'address2','email', 'logo', 'domain', 'more_info', 'is_active'];

    public function getDetails()
    {
        return $this->hasOne(CompanyDetails::class);
    }
}