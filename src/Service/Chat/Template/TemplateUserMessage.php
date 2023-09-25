<?php

namespace App\Service\Chat\Template;

class TemplateUserMessage extends TemplateAbstract
{
    public function __construct(
        protected int $dialogId,
        protected string $message
    ) {
    }

    public function getFields()
    {
        return [
            "DIALOG_ID" => $this->dialogId,
            "MESSAGE" =>  $this->message,
        ];
    }
}