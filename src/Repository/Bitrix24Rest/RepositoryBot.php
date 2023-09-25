<?php

namespace App\Repository\Bitrix24Rest;

class RepositoryBot extends RepositoryRestAbstract
{
    public function add(array $fields = [])
    {
        $r = $this->client->call('imbot.register', $fields);
        return $r['result'];
    }
}