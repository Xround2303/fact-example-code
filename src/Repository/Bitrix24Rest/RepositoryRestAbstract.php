<?php

namespace App\Repository\Bitrix24Rest;


use App\Interfaces\InterfaceRepository;
use Sw24\Bitrix24Auth\Bitrix24Client;


abstract class RepositoryRestAbstract implements InterfaceRepository
{
    protected \Bitrix24\Bitrix24 $client;

    public function __construct(protected Bitrix24Client $bitrix24Client)
    {
        $this->client = $bitrix24Client->getClient();
    }

    public function find(array $select = [], array $filter = [], array $sort = [], int $limit = 10)
    {
        throw new \ErrorException("Method not available");
    }

    public function add(array $fields = [])
    {
        throw new \ErrorException("Method not available");
    }

    public function delete(int $id)
    {
        throw new \ErrorException("Method not available");
    }

    public function update(int $id, array $fields = [])
    {
        throw new \ErrorException("Method not available");
    }

}