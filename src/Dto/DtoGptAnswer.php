<?php

namespace App\Dto;


use App\Service\Gpt\ContextMessageCollection;

class DtoGptAnswer extends DtoAbstract
{
    public function __construct(
        public string $message,
        public ContextMessageCollection $context,
        public int $tokenCountMessage
    ) {
    }
}