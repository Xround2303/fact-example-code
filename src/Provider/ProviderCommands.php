<?php

declare(strict_types=1);

namespace App\Provider;

use App\Service\Chat\Command\CommandChatEndTheme;
use App\Service\Chat\Command\CommandChatSendAbout;
use App\Service\Chat\Command\CommandChatSendHelp;
use App\Service\Chat\Command\CommandChatShowTokenLimit;
use App\Service\Chat\Command\CommandChatStartTheme;
use App\Service\Chat\Command\CommandRegistry;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ProviderCommands extends AbstractServiceProvider
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
            CommandRegistry::class
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
        $this->getContainer()->add(
            CommandRegistry::class,
            (new CommandRegistry())
                ->add($this->getContainer()->get(CommandChatSendHelp::class), CommandChatSendHelp::COMMAND_NAME)
                ->add($this->getContainer()->get(CommandChatSendAbout::class), CommandChatSendAbout::COMMAND_NAME)
                ->add($this->getContainer()->get(CommandChatStartTheme::class), CommandChatStartTheme::COMMAND_NAME)
                ->add($this->getContainer()->get(CommandChatEndTheme::class), CommandChatEndTheme::COMMAND_NAME)
                ->add($this->getContainer()->get(CommandChatShowTokenLimit::class), CommandChatShowTokenLimit::COMMAND_NAME)
        );
    }
}