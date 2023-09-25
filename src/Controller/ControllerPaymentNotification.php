<?php

namespace App\Controller;


use App\Repository\Database\RepositoryPortal;
use App\Repository\Database\RepositoryPortalTokenLimit;
use App\Repository\Database\RepositoryTransactions;
use Psr\Http\Message\ServerRequestInterface;

class ControllerPaymentNotification extends ControllerAbstract
{
    public function __construct(
        protected RepositoryTransactions $repositoryTransactions,
        protected RepositoryPortal $repositoryPortal,
        protected RepositoryPortalTokenLimit $repositoryPortalTokenLimit
    ){
    }

    public function __invoke(ServerRequestInterface $request)
    {
        if($modelTransaction = $this->repositoryTransactions->findByActiveHash($_REQUEST['label'])) {
            $this->repositoryPortalTokenLimit->update($modelTransaction->portal->tokenLimits, [
                "limit" => (int)$modelTransaction->portal->tokenLimits->limit + (int)$modelTransaction->product->tokens
            ]);

            $this->repositoryTransactions->update($modelTransaction, [
                "date_payed" => date("Y-m-d h:i:s")
            ]);
        }

        return [];
    }
}