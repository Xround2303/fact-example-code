<?php

namespace App\Repository\Database;


use App\Model\ModelGptModel;

class RepositoryGptModel extends RepositoryAbstract
{
    public function __construct(ModelGptModel $model)
    {
        parent::__construct($model);
    }
}