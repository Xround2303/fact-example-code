<?php

namespace App\Dto;

use Psr\Http\Message\ServerRequestInterface;

class DtoCommand extends DtoAbstract
{
    public function __construct(
        public int $dialogId,
        public int $userId,
        public int $messageId,
        public string $message,
    ) {
    }

    public static function fromRequest(ServerRequestInterface $request): self
    {
        return new self(
            $_REQUEST['data']['PARAMS']['DIALOG_ID'],
            $_REQUEST['data']['PARAMS']['FROM_USER_ID'],
            $_REQUEST['data']['PARAMS']['MESSAGE_ID'],
            $_REQUEST['data']['PARAMS']['MESSAGE'],
        );
    }
}