<?php

namespace App\Repository\Database;


use App\Model\ModelGptModelLimit;

class RepositoryGptModelLimit extends RepositoryAbstract
{
    public function __construct(ModelGptModelLimit $model)
    {
        parent::__construct($model);
    }
}