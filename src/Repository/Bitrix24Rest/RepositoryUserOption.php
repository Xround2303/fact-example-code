<?php

namespace App\Repository\Bitrix24Rest;

use App\Model\BitrixRest\ModelAppInfo;

class RepositoryUserOption extends RepositoryRestAbstract
{
    public function add(array $fields = [])
    {
        try {
            $r = $this->client->call("user.option.set", [
                "options" => $fields
            ]);
        } catch (\Bitrix24\Exceptions\Bitrix24Exception $e) {
            return false;
        }

        return $r['result'];
    }

    public function all()
    {
        try {
            $r = $this->client->call("user.option.get");
            return $r['result'] ?: [];
        } catch (\Bitrix24\Exceptions\Bitrix24Exception $e) {
        }

        return false;
    }

    public function findByOption($option)
    {
        try {
            $r = $this->client->call("user.option.get", [
                "option" => $option
            ]);
            return $r['result'] ?? false;
        } catch (\Bitrix24\Exceptions\Bitrix24Exception $e) {
        }

        return false;
    }
}