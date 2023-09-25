<?php

namespace App\Service\Chat\Command;


class CommandRegistry
{
    protected array $registry = [];

    public function add(CommandInterface $command, $name): self
    {
        $this->registry[$name] = $command;
        return $this;
    }

    public function get($type): ?CommandInterface
    {
        return $this->registry[$type];
    }

    /**
     * @return CommandChatAbstract[]
     */
    public function list(): array
    {
        return $this->registry;
    }
}