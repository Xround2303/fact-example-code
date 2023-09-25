<?php

namespace App\Service\Chat\Template\Button;

use App\Service\Chat\Command\CommandChatSendAbout;
use App\Service\Chat\Command\CommandChatSendHelp;
use App\Service\Chat\Command\CommandChatShowTokenLimit;
use App\Service\Chat\Template\TemplateAbstract;

class ButtonBalance extends TemplateAbstract
{
    public function getFields()
    {
        return [
            "TEXT" => "Баланс",
            "COMMAND" => CommandChatShowTokenLimit::COMMAND_NAME,
            "BG_COLOR" => "#f39c12",
            "TEXT_COLOR" => "#FFFFFF",
            "DISPLAY" => "LINE"
        ];
    }
}