<?php

namespace App\Service;

use App\Dto\DtoAppInstall;
use App\Dto\DtoAppUnInstall;
use App\Model\ModelPortal;
use App\Repository\Database\RepositoryGptModel;
use App\Repository\Database\RepositoryPortal;
use App\Repository\Database\RepositoryPortalTokenLimit;
use function Symfony\Component\Translation\t;

class ServiceUnInstall
{
    public function __construct(
        protected RepositoryPortal $repositoryPortal,
        protected RepositoryPortalTokenLimit $repositoryPortalTokenLimit,
        protected RepositoryGptModel $repositoryGptModel
    ){
    }

    public function execute(DtoAppUnInstall $dto)
    {
        foreach ($this->repositoryGptModel->findAll() as $gptModel) {
            if($modelPortal = $this->repositoryPortal->get($dto->memberId, $gptModel->id)) {
                $this->repositoryPortal->update($modelPortal, [
                    "date_delete" => date("Y-m-d h:i:s")
                ]);
            }
        }
    }
}