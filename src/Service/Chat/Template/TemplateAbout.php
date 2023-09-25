<?php

namespace App\Service\Chat\Template;

use App\Service\Chat\Template\Button\ButtonAbout;
use App\Service\Chat\Template\Button\ButtonHelp;
use App\Service\Chat\Template\Button\ButtonToggleTheme;

class TemplateAbout extends TemplateAbstract
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
            "KEYBOARD" => [
                (new ButtonHelp())->getFields(),
                (new ButtonToggleTheme())->getFields(),
            ]
        ];
    }
}