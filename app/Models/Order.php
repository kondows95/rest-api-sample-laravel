<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public $fillable = [
        'total_price',
        'first_name',
        'last_name',
        'address1',
        'address2',
        'country',
        'state',
        'city',
    ];
    
    public function orderitems(): HasMany
    {
        return $this->hasMany(Orderitem::class);
    }
}
