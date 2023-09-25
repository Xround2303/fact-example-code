<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModelPortalTariff extends Model
{
    protected $table = 'portal_tariffs';
    public $timestamps = false;

    public function gptModelLimitList(): HasMany
    {
        return $this->hasMany(ModelGptModelLimit::class, "portal_tariff_id", "id");
    }

    public function getGptModelLimit($gptModelId)
    {
        return $this->gptModelLimitList()->where("gpt_model_id", $gptModelId)->first();
    }
}