<?php

namespace App\Controller;


use App\Dto\DtoAppUnInstall;
use App\Service\ServiceUnInstall;
use Sw24\Bitrix24Auth\Bitrix24Client;

class ControllerUninstall extends ControllerAbstract
{

    private \Bitrix24\Bitrix24 $client;

    public function __construct(
        protected ServiceUnInstall $serviceUnInstall,
        protected Bitrix24Client   $bitrix24Client,
    )
    {
        $this->client = $this->bitrix24Client->getClientUnInstall();
    }

    public function __invoke()
    {
        (new \Sw24\Sw24RestSdk\Client())->callUnInstallApplication(
            $this->client->getDomain(),
            $_ENV['MODULE_CODE'],
        );

        $this->serviceUnInstall->execute(
            new DtoAppUnInstall(
                $this->client->getDomain(),
                $this->client->getMemberId()
            )
        );
    }
}