<?php

namespace App\Repository\Bitrix24Rest;

class RepositoryBotCommandAnswer extends RepositoryRestAbstract
{
    public function add(array $fields = [])
    {
        return $this->client->call('imbot.command.answer', $fields);
    }
}