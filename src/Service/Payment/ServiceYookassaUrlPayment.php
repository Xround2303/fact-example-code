<?php

namespace App\Service\Payment;

use App\Middleware\MiddlewarePaymentProviderUrl;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;

class ServiceYookassaUrlPayment
{
    public function __construct(
        protected Client $httpClient,
        protected HandlerStack $stack,
        protected MiddlewarePaymentProviderUrl $middlewarePaymentProviderUrl
    ) {

    }

    public function get(string $receiver, string $label, $sum, $successUrl)
    {
        $this->stack->push(
            Middleware::mapRequest(
                $this->middlewarePaymentProviderUrl
            )
        );

        $this->httpClient->post('/quickpay/confirm.xml', [
            "form_params" => [
                "receiver" => $receiver,
                "label" => $label,
                "quickpay-form" => "button",
                "sum" => $sum,
                "paymentType" => "AC",
                "successURL" => $successUrl
            ]
        ]);

        return $this->middlewarePaymentProviderUrl->getFinalUrl();
    }
}