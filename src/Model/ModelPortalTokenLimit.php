<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ModelPortalTokenLimit extends Model
{
    protected $table = 'portal_token_limits';
    protected $guarded = [];
    public $timestamps = false;
}