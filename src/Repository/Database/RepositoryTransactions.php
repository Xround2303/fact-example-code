<?php

namespace App\Repository\Database;

use App\Model\ModelTransaction;
use Illuminate\Database\Eloquent\Model;

class RepositoryTransactions extends RepositoryAbstract
{
    public function __construct(ModelTransaction $model)
    {
        parent::__construct($model);
    }

    public function findByActiveHash($hash)
    {
        return $this->model::where("hash", $hash)->where("date_payed", null)->first();
    }


}