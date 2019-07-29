<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicKey extends Model
{
    protected $primaryKey = 'kid';
    public $incrementing = false;
    public $fillable = ['kid', 'public_key'];
}
