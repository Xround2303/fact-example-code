<?php

namespace App\Repository\Database;

use App\Model\ModelPortalTokenUsage;

class RepositoryPortalTokenUsage extends RepositoryAbstract
{
    public function __construct(ModelPortalTokenUsage $model)
    {
        parent::__construct($model);
    }
}