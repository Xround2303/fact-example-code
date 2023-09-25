<?php

namespace App\Controller;

use App\Repository\Database\RepositoryTransactions;
use App\Response\Response;
use Psr\Http\Message\ServerRequestInterface;

class ControllerTransaction extends ControllerAbstract
{
    public function __construct(
        protected RepositoryTransactions $repositoryTransactions
    ){
    }

    public function __invoke(ServerRequestInterface $request): array
    {
        $this->repositoryTransactions->create([
            "hash" => $hash = md5(time() + $this->getRequestValue($request, "portal_id")),
            "portal_id" => $this->getRequestValue($request, "portal_id"),
            "product_id" => $this->getRequestValue($request, "product_id")
        ]);

        return Response::toArray($hash);
    }
}