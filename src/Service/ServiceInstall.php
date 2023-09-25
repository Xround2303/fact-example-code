<?php

namespace App\Service;

use App\Dto\DtoAppInstall;
use App\Model\ModelPortal;
use App\Repository\Bitrix24Rest\RepositoryAppOption;
use App\Repository\Database\RepositoryGptModel;
use App\Repository\Database\RepositoryGptModelLimit;
use App\Repository\Database\RepositoryPortal;
use App\Repository\Database\RepositoryPortalTariff;
use App\Repository\Database\RepositoryPortalTokenLimit;
use Illuminate\Database\Eloquent\Model;


class ServiceInstall
{
    public function __construct(
        protected RepositoryAppOption $repositoryAppOption,
        protected RepositoryPortal $repositoryPortal,
        protected RepositoryPortalTokenLimit $repositoryPortalTokenLimit,
        protected RepositoryGptModel $repositoryGptModel,
        protected RepositoryGptModelLimit $repositoryGptModelLimit,
        protected RepositoryPortalTariff $repositoryPortalTariff
    ){
    }

    public function execute(DtoAppInstall $dto)
    {
        if(!$modelPortal = $this->repositoryPortal->get($dto->memberId, $dto->gptModelId)) {
            return $this->install($dto);
        }

        return $this->reInstall($dto, $modelPortal);
    }

    protected function install($dto): Model
    {
        $modelGptModel = $this->repositoryGptModel->find($dto->gptModelId);

        $modelPortal = $this->repositoryPortal->create([
            "domain" => $dto->domain,
            "member_id" => $dto->memberId,
            "gpt_model_id" => $modelGptModel->id
        ]);

        $appInfo = $this->repositoryAppOption->findAppInfo();

        if(!$modelPortalTariff = $this->repositoryPortalTariff->getByName($appInfo->getLicenseType())) {
            $modelPortalTariff = $this->repositoryPortalTariff->getByName("other");
        }

        $modelGptModelLimit = $modelPortalTariff->getGptModelLimit($modelGptModel->id);

        return $this->repositoryPortalTokenLimit->create([
            "portal_id" => $modelPortal->id,
            "limit" => $modelGptModelLimit->limit,
        ]);
    }

    protected function reInstall(DtoAppInstall $dto, ModelPortal $modelPortal): bool
    {
        return $this->repositoryPortal->update($modelPortal, [
            "date_create" => date("Y-m-d:h:i:s"),
            "date_delete" => null
        ]);
    }

}