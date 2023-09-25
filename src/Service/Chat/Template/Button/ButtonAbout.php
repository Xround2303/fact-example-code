<?php

namespace App\Service\Chat\Template\Button;

use App\Service\Chat\Command\CommandChatSendAbout;
use App\Service\Chat\Template\TemplateAbstract;

class ButtonAbout extends TemplateAbstract
{
    public function getFields()
    {
        return [
            "TEXT" => "Обо мне",
            "COMMAND" => CommandChatSendAbout::COMMAND_NAME,
            "BG_COLOR" => "#00c0ff",
            "TEXT_COLOR" => "#FFFFFF",
            "DISPLAY" => "LINE"
        ];
    }
}