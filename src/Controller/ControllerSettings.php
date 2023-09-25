<?php

namespace App\Controller;

use App\Enum\EnumAppOption;
use App\Enum\EnumResponseStatus;
use App\Exception\ExceptionApplication;
use App\Manager\ManagerPortal;
use App\Model\ModelProduct;
use App\Repository\Bitrix24Rest\RepositoryAppOption;
use App\Repository\Bitrix24Rest\RepositoryUser;
use App\Repository\Database\RepositoryPortal;
use App\Repository\Database\RepositoryProduct;
use App\Response\Response;
use App\Service\Chat\ServiceSaveUserToken;
use Psr\Http\Message\ServerRequestInterface;
use Sw24\Bitrix24Auth\Bitrix24Client;
use Sw24\Bitrix24Auth\Strategy\StrategyAuthArray;

class ControllerSettings extends ControllerAbstract
{
    public function __construct(
        protected RepositoryAppOption $repositoryAppOption,
        protected RepositoryProduct $repositoryProduct,
        protected RepositoryPortal $repositoryPortal,
        protected Bitrix24Client   $bitrix24Client,
        protected ServiceSaveUserToken $serviceSaveUserToken,
        protected RepositoryUser $repositoryUser
    ) {
    }

    public function list(ServerRequestInterface $request)
    {
        $modelPortal = $this->repositoryPortal->getActivePortal(
            $this->bitrix24Client->getClient()->getMemberId()
        );

        $productCollection = $this->repositoryProduct->findAll();

        return Response::toArray([
            EnumAppOption::SERVICE_TOKEN_MODE => ManagerPortal::getInstance()->isTokenUserMode(),
            EnumAppOption::SERVICE_USER_TOKEN => ManagerPortal::getInstance()->getUserToken(),
            "service_token_limit_usage" => $modelPortal->getTotalCountTokenUsage(),
            "service_token_limit_total" => $modelPortal->tokenLimits->limit,
            "service_product_list" => $productCollection->toArray(),
            "service_portal_id" => $modelPortal->id,
            "service_portal_domain" => $this->bitrix24Client->getClient()->getDomain(),
            "service_app_id" => ManagerPortal::getInstance()->getAppId(),
            "user_id" => $this->repositoryUser->findUserCurrent()->id,
            "module_code" => $_ENV['MODULE_CODE']
        ]);
    }

    public function saveTokenMode(ServerRequestInterface $request): array
    {
        return Response::toArray(
            $this->repositoryAppOption->add([
                EnumAppOption::SERVICE_TOKEN_MODE => $this->getRequestValue($request, "value")
            ])
        );
    }

    public function saveUserToken(ServerRequestInterface $request): array
    {
        if($result = $this->serviceSaveUserToken->save($this->getRequestValue($request, "token"))) {
            return Response::toArray($result);
        }

        return Response::toArray("Не корректный токен", EnumResponseStatus::STATUS_ERROR);
    }
}