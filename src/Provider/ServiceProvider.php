<?php

declare(strict_types=1);

namespace App\Provider;

use App\Middleware\MiddlewarePaymentProviderUrl;
use App\Service\Gpt\GptClient;
use App\Service\Payment\ServiceYookassaUrlPayment;
use Gioni06\Gpt3Tokenizer\Gpt3Tokenizer;
use Gioni06\Gpt3Tokenizer\Gpt3TokenizerConfig;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\RequestOptions;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

class ServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    /**
     * The provides method is a way to let the container
     * know that a service is provided by this service
     * provider. Every service that is registered via
     * this service provider must have an alias added
     * to this array or it will be ignored.
     */
    public function provides(string $id): bool
    {
        $services = [
        ];

        return in_array($id, $services);
    }

    /**
     * The register method is where you define services
     * in the same way you would directly with the container.
     * A convenience getter for the container is provided, you
     * can invoke any of the methods you would when defining
     * services directly, but remember, any alias added to the
     * container here, when passed to the `provides` nethod
     * must return true, or it will be ignored by the container.
     */
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->getContainer()->add(Gpt3Tokenizer::class)->addArgument(new Gpt3TokenizerConfig());


        $stack = HandlerStack::create();
        $this->getContainer()->add(ServiceYookassaUrlPayment::class)
            ->addArgument(
                new Client([
                    'base_uri' => 'https://yoomoney.ru',
                    RequestOptions::ALLOW_REDIRECTS => true,
                    'handler' => $stack,
                ])
            )
            ->addArgument(
                $stack
            )
            ->addArgument(new MiddlewarePaymentProviderUrl());
    }
}