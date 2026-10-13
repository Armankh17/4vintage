<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'brand', 'price', 'photo'];

    protected $casts = [
        'price' => 'decimal:2',
    ];
}
