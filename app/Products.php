<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    //
    protected $table = 'products';
    protected $fillable = [
        'id',
        'name',
        'brand',
        'type',
        'aboveground',
        'description',
        'images',
        'data',
        'scheduled'
    ];
}
