<?php

namespace App\Repository\Database;


use App\Model\ModelPortal;

class RepositoryPortal extends RepositoryAbstract
{
    public function __construct(ModelPortal $model)
    {
        parent::__construct($model);
    }

    public function get($memberId, $gptModelId)
    {
        return $this->model::where('member_id', $memberId)
            ->where("gpt_model_id", $gptModelId)
            ->first();
    }

    public function getActivePortal($memberId): ?ModelPortal
    {
        return $this->model::where('member_id', $memberId)
            ->where("date_delete", null)
            ->first();
    }
}