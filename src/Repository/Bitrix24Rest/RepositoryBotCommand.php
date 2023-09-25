<?php

namespace App\Repository\Bitrix24Rest;

class RepositoryBotCommand extends RepositoryRestAbstract
{
    public function add(array $fields = [])
    {
        return $this->client->call('imbot.command.register', $fields);
    }
}