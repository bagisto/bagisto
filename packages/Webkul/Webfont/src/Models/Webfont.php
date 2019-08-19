<?php

namespace Webkul\Webfont\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Webfont\Contracts\Webfont as WebfontContract;

class Webfont extends Model implements WebfontContract
{
    protected $table = 'google_web_fonts';

    protected $fillable = ['font', 'activated'];
}