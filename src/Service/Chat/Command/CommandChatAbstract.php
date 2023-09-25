<?php

namespace App\Service\Chat\Command;


use App\Dto\DtoCommand;
use App\Repository\Bitrix24Rest\RepositoryAppOption;
use App\Repository\Bitrix24Rest\RepositoryChatMessage;
use App\Repository\Bitrix24Rest\RepositoryUserOption;
use App\Repository\Database\RepositoryPortal;
use App\Service\Chat\ServiceSendMessage;
use Sw24\Bitrix24Auth\Bitrix24Client;

abstract class CommandChatAbstract implements CommandInterface
{
    const COMMAND_NAME = "COMMAND_NAME";

    public function __construct(
        protected RepositoryUserOption $repositoryUserOption,
        protected RepositoryChatMessage $repositoryChatMessage,
        protected ServiceSendMessage $serviceSendMessage,
        protected RepositoryPortal $repositoryPortal,
        protected Bitrix24Client $bitrix24Client
    ) {
    }

    public function execute(DtoCommand $dto)
    {

    }

    public function getTitle()
    {
        return "Название команды";
    }

    public function isGlobalCommand(): bool
    {
        return false;
    }
}