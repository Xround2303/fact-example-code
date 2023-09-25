<?php

namespace App\Service\Chat\Template\Button;

use App\Manager\ManagerPortalUser;
use App\Service\Chat\Command\CommandChatEndTheme;
use App\Service\Chat\Command\CommandChatStartTheme;
use App\Service\Chat\Template\TemplateAbstract;

class ButtonToggleTheme extends TemplateAbstract
{
    public function getFields()
    {
        if(ManagerPortalUser::getInstance()->isChatThemeActive()) {
            return [
                "TEXT" => "Завершить тему",
                "COMMAND" => CommandChatEndTheme::COMMAND_NAME,
                "BG_COLOR" => "#ee5253",
                "TEXT_COLOR" => "#FFFFFF",
                "DISPLAY" => "LINE"
            ];
        }

        return [
            "TEXT" => "Начать тему",
            "COMMAND" => CommandChatStartTheme::COMMAND_NAME,
            "BG_COLOR" => "#10ac84",
            "TEXT_COLOR" => "#FFFFFF",
            "DISPLAY" => "LINE"
        ];
    }
}