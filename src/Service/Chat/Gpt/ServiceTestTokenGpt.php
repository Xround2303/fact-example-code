<?php

namespace App\Service\Chat\Gpt;


use App\Dto\DtoGptAnswer;
use App\Exception\ExceptionGtpNoAnswer;
use App\Service\Gpt\ContextMessage;
use App\Service\Gpt\ContextMessageCollection;
use App\Service\Gpt\EnumRole;
use App\Service\Gpt\GptClient;
use Gioni06\Gpt3Tokenizer\Gpt3Tokenizer;
use GuzzleHttp\Exception\GuzzleException;

class ServiceTestTokenGpt
{
    public function test($token): bool
    {
        try {
            (new GptClient($token))->prompt(
                (new ContextMessageCollection())->push(
                    new ContextMessage("test", EnumRole::ROLE_USER, 0, time(), 0, 0)
                )
            );
        } catch (ExceptionGtpNoAnswer $e) {
        } catch (GuzzleException $e) {
            return false;
        }

        return true;
    }
}