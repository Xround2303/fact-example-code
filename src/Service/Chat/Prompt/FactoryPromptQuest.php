<?php

namespace App\Service\Chat\Prompt;

use App\Enum\EnumAppOption;
use App\Manager\ManagerPortal;
use App\Repository\Bitrix24Rest\RepositoryAppOption;

class FactoryPromptQuest
{
    public function __construct(
        protected RepositoryAppOption $repositoryAppOption,
        protected PromptQuestServiceToken $promptQuestServiceToken,
        protected PromptQuestUserToken $promptQuestUserToken
    ){
    }

    public function make(): PromptQuestAbstract
    {
        if(ManagerPortal::getInstance()->isTokenUserMode()) {
            return $this->promptQuestUserToken;
        }

        return $this->promptQuestServiceToken;
    }
}