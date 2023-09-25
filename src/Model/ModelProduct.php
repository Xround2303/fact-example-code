<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ModelProduct extends Model
{
    protected $table = 'products';
    protected $guarded = [];
    public $timestamps = false;
}