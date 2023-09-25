<?php

namespace App\Controller;

use App\Dto\DtoAppInstall;
use App\Repository\Bitrix24Rest\RepositoryBot;
use App\Repository\Bitrix24Rest\RepositoryBotCommand;
use App\Repository\Bitrix24Rest\RepositoryUser;
use App\RouterFacade;
use App\Service\Chat\Command\CommandRegistry;
use App\Service\Chat\ServiceSendMessage;
use App\Service\Chat\Template\TemplateHello;
use App\Service\ServiceInstall;
use Bitrix24\Bitrix24;
use Psr\Http\Message\ServerRequestInterface;
use Sw24\Bitrix24Auth\Bitrix24Client;
use Sw24\Sw24RestSdk\Client;


class ControllerInstall extends ControllerAbstract
{
    protected Bitrix24 $client;

    public function __construct(
        protected Bitrix24Client              $bitrix24Client,
        protected Client                      $sw24RestSdkClient,
        protected CommandRegistry             $commandRegistry,
        protected RepositoryBot               $repositoryBot,
        protected RepositoryBotCommand        $repositoryBotCommand,
        protected ServiceInstall $serviceInstall,
        protected ServiceSendMessage $serviceSendMessage,
        protected RepositoryUser $repositoryUser
    ) {
        $this->client = $this->bitrix24Client->getClient();
    }

    public function __invoke(ServerRequestInterface $request): array
    {
        $botId = $this->repositoryBot->add([
            'CODE'                  => $_ENV['MODULE_CODE'],
            'TYPE'                  => 'B',
            'EVENT_MESSAGE_ADD'     => RouterFacade::getInstance()->getPathRoute("chatHandler"),
            'EVENT_WELCOME_MESSAGE' => RouterFacade::getInstance()->getPathRoute("chatJoinHandler"),
            'EVENT_BOT_DELETE'      => RouterFacade::getInstance()->getPathRoute("chatHandler"),
            'OPENLINE'              => 'Y',
            'PROPERTIES'            => [
                'NAME'              => 'Бот ChatGPT',
                'COLOR'             => 'BLUE',
                'PERSONAL_BIRTHDAY' => "2303-03-20",
                'WORK_POSITION'     => 'Персональный ассистент с искусственным интеллектом',
                'PERSONAL_GENDER'   => 'M',
                'PERSONAL_PHOTO'        => base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . DIR_BX24_APP . "/upload/chatgpt.png")),
            ]
        ]);


        foreach ($this->commandRegistry->list() as $command) {
            $this->repositoryBotCommand->add([
                'BOT_ID'    => $botId,
                'COMMAND'   => $command::COMMAND_NAME,
                'COMMON'    => 'N',
                'HIDDEN'    => $command->isGlobalCommand() ? "N" : "Y",
                'EXTRANET_SUPPORT' => 'N',
                'LANG' => [
                    [
                        'LANGUAGE_ID' => "ru",
                        'TITLE'     => $command->getTitle(),
                        'PARAMS'    => ''
                    ],
                ],
                'EVENT_COMMAND_ADD' =>  RouterFacade::getInstance()->getPathRoute("chatCommandHandler")
            ]);
        }

        $this->client->call('event.unbind', [
            'EVENT'   => 'OnAppUpdate',
            'HANDLER' => RouterFacade::getInstance()->getPathRoute("update"),
        ]);

        $this->client->call('event.bind', [
            'EVENT'   => 'OnAppUpdate',
            'HANDLER' => RouterFacade::getInstance()->getPathRoute("update"),
        ]);

        $this->client->call('event.unbind', [
            'EVENT'   => 'OnAppUninstall',
            'HANDLER' => RouterFacade::getInstance()->getPathRoute("uninstall"),
        ]);

        $this->client->call('event.bind', [
            'EVENT'   => 'OnAppUninstall',
            'HANDLER' => RouterFacade::getInstance()->getPathRoute("uninstall"),
        ]);

        $this->sw24RestSdkClient->callInstallApplication(
            $this->client->getDomain(),
            $_ENV['MODULE_CODE'],
            $this->client->getMemberId(),
            $this->client->getAccessToken(),
            $this->client->getRefreshToken()
        );

        $this->serviceInstall->execute(
            new DtoAppInstall(
                1,
                $this->client->getDomain(),
                $this->client->getMemberId()
            )
        );

        $modelUser = $this->repositoryUser->findUserCurrent();

        $this->serviceSendMessage->execute(
            new TemplateHello(
                $botId,
                $modelUser->id
            )
        );

        return [];
    }
}