<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    public $fillable = [
        'name'
    ];
    
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}