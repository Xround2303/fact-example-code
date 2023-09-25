<?php

declare(strict_types=1);

namespace App\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Sw24\Bitrix24Auth\Bitrix24Client;
use Sw24\Bitrix24Auth\Strategy\StrategyAuthRequest;

class ProviderBitrix24Client extends AbstractServiceProvider
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
            Bitrix24Client::class
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
        $this->getContainer()->add(Bitrix24Client::class)->addArgument(StrategyAuthRequest::class);
    }
}