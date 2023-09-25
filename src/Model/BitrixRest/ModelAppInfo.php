<?php

namespace App\Model\BitrixRest;

class ModelAppInfo
{
    public function __construct(
        public ?int $id,
        public ?string $license,
        public ?string $language
    ){
    }

    public function getLicenseType(): string
    {
        $type = str_replace("ru_", "", $this->license);
        return str_replace($this->language . "_", "", $type);
    }
}