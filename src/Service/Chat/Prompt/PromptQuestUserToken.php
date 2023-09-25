<?php

namespace App\Service\Chat\Prompt;

use App\Dto\DtoPrompt;
use App\Service\Gpt\ContextMessageCollection;

class PromptQuestUserToken extends PromptQuestAbstract
{
    protected function checkUserTokenLimit(ContextMessageCollection $context): bool
    {
        return false;
    }

    protected function saveRequestTokenUsage(DtoPrompt $dto, int $tokenCountCompletion, int $tokenCountPrompt, int $assistantMessageId)
    {
    }
}