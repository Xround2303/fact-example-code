<?php

namespace App\Dto;

class DtoAppUnInstall extends DtoAbstract
{
    public function __construct(
        public string $domain,
        public string $memberId
    ) {
    }
}