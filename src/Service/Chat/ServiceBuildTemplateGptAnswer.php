<?php

namespace App\Service\Chat;

use App\Dto\DtoGptAnswer;
use App\Service\Chat\Template\TemplateGptAnswer;
use App\Service\Chat\Template\TemplateInterface;

class ServiceBuildTemplateGptAnswer
{
    public function build(DtoGptAnswer $dto, int $dialogId): TemplateInterface
    {
        return new TemplateGptAnswer(
            $dialogId,
            $dto->message,
            $dto->tokenCountMessage + $dto->context->getTotalToken(),
        );
    }
}