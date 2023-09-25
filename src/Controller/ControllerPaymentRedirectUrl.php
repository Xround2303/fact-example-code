<?php

namespace App\Controller;


use App\Repository\Database\RepositoryPortal;
use App\Repository\Database\RepositoryPortalTokenLimit;
use App\Repository\Database\RepositoryTransactions;
use App\Response\Response;
use App\Service\Payment\ServiceYookassaUrlPayment;
use Psr\Http\Message\ServerRequestInterface;

class ControllerPaymentRedirectUrl extends ControllerAbstract
{
    public function __construct(
        protected ServiceYookassaUrlPayment $serviceYookassaUrlPayment
    ){
    }

    public function __invoke(ServerRequestInterface $request)
    {
        return Response::toArray(
            $this->serviceYookassaUrlPayment->get(
                $this->getRequestValue($request, "receiver"),
                $this->getRequestValue($request, "label"),
                $this->getRequestValue($request, "sum"),
                $this->getRequestValue($request, "successUrl"),
            )
        );
    }
}