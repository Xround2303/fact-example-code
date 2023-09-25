<?php

namespace App\Service\Chat\Gpt;


use App\Dto\DtoGptAnswer;
use App\Service\Gpt\ContextMessageCollection;
use App\Service\Gpt\GptClient;
use Gioni06\Gpt3Tokenizer\Gpt3Tokenizer;

class ServiceRequestGptAnswer
{
    public function __construct(
        protected GptClient $gptClient,
        protected Gpt3Tokenizer $gpt3Tokenizer
    ) {
    }

    public function build(ContextMessageCollection $context): DtoGptAnswer
    {
        return new DtoGptAnswer(
            $message = $this->gptClient->prompt($context),
            $context,
            $this->gpt3Tokenizer->count($message)
        );
    }




}