<?php

namespace App\Dto;

class DtoAppInstall extends DtoAbstract
{
    public function __construct(
        public int $gptModelId,
        public string $domain,
        public string $memberId
    ) {
    }
}