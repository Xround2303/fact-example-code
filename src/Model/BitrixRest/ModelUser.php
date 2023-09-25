<?php

namespace App\Model\BitrixRest;

class ModelUser
{
    public function __construct(
        public ?int $id,
        public ?string $name,
        public ?string $lastName,
        public ?string $personalPhoto,
        public ?string $dateRegister
    ){
    }
}