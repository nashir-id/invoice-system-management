<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [

    'business_name',
    'tagline',
    'business_email',
    'website',
    'logo',

    'mandiri_name',
    'mandiri_number',
    'mandiri_holder',

    'bca_name',
    'bca_number',
    'bca_holder',

    'seabank_name',
    'seabank_number',
    'seabank_holder',
    ];
}