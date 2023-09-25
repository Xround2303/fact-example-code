<?php

namespace App\Controller;

use App\Dto\DtoCommand;
use App\Repository\Bitrix24Rest\RepositoryBotCommandAnswer;
use App\Repository\Bitrix24Rest\RepositoryChatMessage;
use App\Service\Chat\Command\CommandRegistry;
use Psr\Http\Message\ServerRequestInterface;


class ControllerChatCommand extends ControllerAbstract
{
    public function __construct(protected CommandRegistry $commandRegistry) {
    }

    public function __invoke(ServerRequestInterface $request)
    {
        foreach ($_REQUEST['data']['COMMAND'] as $command) {
            $this->commandRegistry->get($command['COMMAND'])->execute(DtoCommand::fromRequest($request));
        }

        return [];
    }


}