<?php

namespace App\Controller;


use App\Dto\DtoPrompt;
use App\Repository\Bitrix24Rest\RepositoryAppOption;
use App\Response\Response;
use App\Service\Chat\Prompt\FactoryPromptQuest;
use Psr\Http\Message\ServerRequestInterface;


class ControllerChat extends ControllerAbstract
{
    public function __construct(
        protected FactoryPromptQuest $factoryPromptQuest,
        protected RepositoryAppOption $repositoryAppOption
    ) {
    }

    public function __invoke(ServerRequestInterface $request): array
    {
        return Response::toArray(
            $this->factoryPromptQuest
                ->make()
                ->execute(DtoPrompt::fromRequest($request))
        );
    }

}
