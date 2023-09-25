<?php

namespace App\Service\Chat;


use App\Enum\EnumAppOption;
use App\Repository\Bitrix24Rest\RepositoryAppOption;
use App\Service\Chat\Gpt\ServiceTestTokenGpt;

class ServiceSaveUserToken
{
    public function __construct(
        protected RepositoryAppOption $repositoryAppOption,
        protected ServiceTestTokenGpt $serviceTestTokenGpt
    ){
    }

    public function save($token): bool
    {
        if($this->serviceTestTokenGpt->test($token)) {
            return $this->repositoryAppOption->add([
                EnumAppOption::SERVICE_USER_TOKEN => $token
            ]);
        }

        return false;
    }
}