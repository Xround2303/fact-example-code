<?php

namespace App\Repository\Database;


use App\Model\ModelPortal;
use App\Model\ModelPortalTokenLimit;

class RepositoryPortalTokenLimit extends RepositoryAbstract
{
    public function __construct(ModelPortalTokenLimit $model)
    {
        parent::__construct($model);
    }
}