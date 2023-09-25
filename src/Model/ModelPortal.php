<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ModelPortal extends Model
{
    protected $table = 'portals';

    protected $guarded = [];
    public $timestamps = false;

    public function tokenLimits(): HasOne
    {
        return $this->hasOne(ModelPortalTokenLimit::class, "portal_id", "id");
    }

    public function tokenUsages(): HasMany
    {
        return $this->hasMany(ModelPortalTokenUsage::class, "portal_id", "id");
    }

    public function getTotalCountTokenUsage()
    {
        return $this->tokenUsages->sum("token_count_prompt") + $this->tokenUsages->sum("token_count_completion");
    }
}