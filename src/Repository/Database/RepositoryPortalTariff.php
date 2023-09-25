<?php

namespace App\Repository\Database;


use App\Model\ModelPortalTariff;

class RepositoryPortalTariff extends RepositoryAbstract
{
    public function __construct(ModelPortalTariff $model)
    {
        parent::__construct($model);
    }

    public function getByName($name): ?ModelPortalTariff
    {
        return $this->model::where("name", $name)->first();
    }
}