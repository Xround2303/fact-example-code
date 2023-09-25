<?php

namespace App\Service\Chat;

use App\Enum\EnumAppOption;
use App\Repository\Bitrix24Rest\RepositoryAppOption;
use App\Repository\Bitrix24Rest\RepositoryUserOption;

class ServiceRememberActiveHandlerPromptQuest
{
    public function __construct(
        protected RepositoryUserOption $repositoryUserOption
    ) {
    }

    public function update(bool $value)
    {
        return $this->repositoryUserOption->add([
            EnumAppOption::CHAT_USER_GPT_HANDLER_IS_ACTIVE => $value,
            EnumAppOption::CHAT_USER_GPT_HANDLER_ACTIVE_EXPIRED_TIME => $value ? time() : 0,
        ]);
    }

    public function isHandleActive(): bool
    {
        if($this->fetchHandleIsActive()) {

            // Если время жизни обработчика меньше текущего времени, то обработчик завис
            if($this->fetchHandleActiveTimeExpired() + 180 < time()) {
                return false;
            }

            return true;
        }

        return false;
    }

    public function fetchHandleIsActive(): bool
    {
        $handlerActive = $this->repositoryUserOption->findByOption(
            EnumAppOption::CHAT_USER_GPT_HANDLER_IS_ACTIVE
        );

        return $handlerActive ?? false;
    }

    public function fetchHandleActiveTimeExpired()
    {
        $handlerActiveExpired = $this->repositoryUserOption->findByOption(
            EnumAppOption::CHAT_USER_GPT_HANDLER_ACTIVE_EXPIRED_TIME
        );

        return $handlerActiveExpired ?? 0;
    }

}