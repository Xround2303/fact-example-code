<?php

namespace App\Repository\Database;

use App\Model\ModelProduct;

class RepositoryProduct extends RepositoryAbstract
{
    public function __construct(ModelProduct $model)
    {
        parent::__construct($model);
    }
}