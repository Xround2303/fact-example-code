<?php

namespace App\Dto;

use Psr\Http\Message\ServerRequestInterface;

class DtoPrompt extends DtoAbstract
{
    public function __construct(
      public int $dialogId,
      public int $chatId,
      public int $userId,
      public int $messageId,
      public string $message,
        public int $toUserId
    ) {
    }

    public static function fromRequest(ServerRequestInterface $request): self
    {
        return new self(
            $_REQUEST['data']['PARAMS']['DIALOG_ID'],
            $_REQUEST['data']['PARAMS']['CHAT_ID'],
            $_REQUEST['data']['PARAMS']['FROM_USER_ID'],
            $_REQUEST['data']['PARAMS']['MESSAGE_ID'],
            $_REQUEST['data']['PARAMS']['MESSAGE'],
            $_REQUEST['data']['PARAMS']['TO_USER_ID'],
        );
    }
}