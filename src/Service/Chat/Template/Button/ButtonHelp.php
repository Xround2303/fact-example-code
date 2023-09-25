<?php

namespace App\Service\Chat\Template\Button;

use App\Service\Chat\Command\CommandChatSendAbout;
use App\Service\Chat\Command\CommandChatSendHelp;
use App\Service\Chat\Template\TemplateAbstract;

class ButtonHelp extends TemplateAbstract
{
    public function getFields()
    {
        return [
            "TEXT" => "Помощь",
            "COMMAND" => CommandChatSendHelp::COMMAND_NAME,
            "BG_COLOR" => "#8e44ad",
            "TEXT_COLOR" => "#FFFFFF",
            "DISPLAY" => "LINE"
        ];
    }
}