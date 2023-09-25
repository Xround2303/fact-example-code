<?php

declare(strict_types=1);

namespace App\Provider;

use App\Manager\ManagerPortalUser;
use App\Repository\Bitrix24Rest\RepositoryAppOption;
use App\Repository\Bitrix24Rest\RepositoryUser;
use App\Repository\Bitrix24Rest\RepositoryUserOption;
use App\Repository\Database\RepositoryPortal;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ProviderManagerPortalUser extends AbstractServiceProvider
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
            ManagerPortalUser::class
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
        $manager = new ManagerPortalUser(
            $this->getContainer()->get(RepositoryUserOption::class)
        );

        $this->getContainer()->add(ManagerPortalUser::class,$manager);
    }
}