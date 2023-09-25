<?php

namespace App\Service\Chat\Command;


use App\Dto\DtoCommand;
use App\Enum\EnumAppOption;
use App\Manager\ManagerPortalUser;
use App\Service\Chat\Template\TemplateSystemMessage;

class CommandChatEndTheme extends CommandChatAbstract
{
    const COMMAND_NAME = "end";

    public function execute(DtoCommand $dto)
    {
        if(!ManagerPortalUser::getInstance()->isChatThemeActive()) {
            return $this->serviceSendMessage->execute(
                new TemplateSystemMessage($dto->dialogId, 'Невозможно закончить тему, пока тема не начата')
            );
        }

        $this->repositoryUserOption->add([
            EnumAppOption::CHAT_THEME_START_TIME => 0
        ]);

        return $this->serviceSendMessage->execute(
            new TemplateSystemMessage($dto->dialogId, 'Тема завершена')
        );
    }

    public function getTitle()
    {
        return "Завершить тему";
    }

    public function isGlobalCommand(): bool
    {
        return true;
    }
}