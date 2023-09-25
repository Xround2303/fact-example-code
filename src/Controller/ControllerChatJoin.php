<?php

namespace App\Controller;


use App\Response\Response;
use App\Service\Chat\ServiceSendMessage;
use App\Service\Chat\Template\TemplateHello;
use Psr\Http\Message\ServerRequestInterface;
use Sw24\Bitrix24Auth\Bitrix24Client;
use Sw24\Sw24RestSdk\Client;


class ControllerChatJoin extends ControllerAbstract
{
    public function __construct(
        protected ServiceSendMessage $serviceSendMessage,
        protected Client $sw24RestSdkClient,
        protected Bitrix24Client $bitrix24Client
    ){
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $this->sw24RestSdkClient->callReviewUserInit(
            $this->bitrix24Client->getClient()->getDomain(),
            $_ENV['MODULE_CODE'],
            $_REQUEST['data']['PARAMS']['FROM_USER_ID'],
            $_REQUEST['data']['PARAMS']['DIALOG_ID']
        );

        return Response::toArray($this->serviceSendMessage->execute(
            new TemplateHello(
                $_REQUEST['data']['PARAMS']['TO_USER_ID'],
                $_REQUEST['data']['PARAMS']['FROM_USER_ID']
            )
        ));
    }
}