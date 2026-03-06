<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    protected $table = 'professionals';

    protected $fillable = [
        'name',
        'email',
        'image',
        'status'
    ];
}