<?php

namespace App\Repository\Bitrix24Rest;

use App\Exception\ExceptionGtpNoAnswer;
use Bitrix24\Exceptions\Bitrix24Exception;

class RepositoryChatMessage extends RepositoryRestAbstract
{
    public function findByDialogId(string $dialogId, int $limit = 10): array
    {
        try {
            $r = $this->client->call("im.dialog.messages.get", [
                "DIALOG_ID" => $dialogId,
                "LIMIT" => $limit
            ]);

            return $r['result']['messages'] ?? [];
        } catch (Bitrix24Exception $e) {
        }

        return [];
    }

    /**
     * @throws \Bitrix24\Exceptions\Bitrix24WrongClientException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalDeletedException
     * @throws Bitrix24Exception
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsExpiredException
     * @throws \Bitrix24\Exceptions\Bitrix24EmptyResponseException
     * @throws \Bitrix24\Exceptions\Bitrix24IoException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsInvalidException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalRenamedException
     * @throws \Bitrix24\Exceptions\Bitrix24ApiException
     * @throws \Bitrix24\Exceptions\Bitrix24SecurityException
     * @throws \Bitrix24\Exceptions\Bitrix24PaymentRequiredException
     * @throws \Bitrix24\Exceptions\Bitrix24MethodNotFoundException
     */
    public function add(array $fields = [])
    {
        if(!$r = $this->client->call("imbot.message.add", $fields)) {
            throw new Bitrix24Exception();
        }

        return $r['result'];
    }

    public function update(int $id, array $fields = [])
    {
        return $this->client->call("im.message.update", [
            "MESSAGE_ID" => $id,
            ...$fields
        ]);
    }
}