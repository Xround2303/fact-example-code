<?php

namespace App\Service\Chat\Prompt;

use App\Dto\DtoPrompt;

abstract class PromptAbstract
{
    abstract public function execute(DtoPrompt $dto);
}