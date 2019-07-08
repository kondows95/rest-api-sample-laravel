<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orderitem extends Model
{
    public $fillable = [
        'order_id',
        'item_id',
        'unit_price',
        'quantity',
    ];
}
