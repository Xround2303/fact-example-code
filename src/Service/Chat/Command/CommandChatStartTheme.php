<?php

namespace App\Service\Chat\Command;


use App\Dto\DtoCommand;
use App\Enum\EnumAppOption;
use App\Manager\ManagerPortalUser;
use App\Service\Chat\Template\TemplateSystemMessage;

class CommandChatStartTheme extends CommandChatAbstract
{
    const COMMAND_NAME = "start";

    public function execute(DtoCommand $dto)
    {
        if(ManagerPortalUser::getInstance()->isChatThemeActive()) {
            return $this->serviceSendMessage->execute(
                new TemplateSystemMessage($dto->dialogId, 'Невозможно начать новую тему, пока не закончена старая')
            );
        }

        $this->repositoryUserOption->add([
            EnumAppOption::CHAT_THEME_START_TIME => time()
        ]);

        return $this->serviceSendMessage->execute(
            new TemplateSystemMessage($dto->dialogId, 'Начата новая тема')
        );
    }

    public function getTitle()
    {
        return "Начать тему";
    }

    public function isGlobalCommand(): bool
    {
        return true;
    }
}