<?php

namespace App\Repository\Bitrix24Rest;

use App\Model\BitrixRest\ModelUser;
use Bitrix24\Exceptions\Bitrix24Exception;

class RepositoryUser extends RepositoryRestAbstract
{
    public function findUserCurrent(): ?ModelUser
    {
        try {
            $r = $this->client->call("user.current");

            return new ModelUser(
                $r['result']['ID'],
                $r['result']['NAME'],
                $r['result']['LAST_NAME'],
                $r['result']['PERSONAL_PHOTO'] ?? "",
                $r['result']['DATE_REGISTER'],
            );

        } catch (Bitrix24Exception $e) {
        }

        return null;
    }
}