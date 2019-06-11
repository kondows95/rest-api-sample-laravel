<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use SoftDeletes;

    public $fillable = [
        'name',
        'price',
        'image',
        'category_id'
    ];

    public function category(): BelongsTo
    {
        //echo $this->belongsTo(Category::class, 'category_id')->toSql().PHP_EOL;
        return $this->belongsTo(Category::class, 'category_id');
    }
}

