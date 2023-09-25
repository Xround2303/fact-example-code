<?php

namespace App\Service\Gpt;

use App\Exception\ExceptionGtpNoAnswer;

class GptClient
{
    protected \GuzzleHttp\Client $httpClient;

    public function __construct(protected string $token) {
        $this->httpClient = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.openai.com/',
        ]);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws ExceptionGtpNoAnswer
     */
    public function prompt(ContextMessageCollection $contextMessageCollection)
    {
        $result = $this->httpClient->post('/v1/chat/completions', [
            "headers" => [
                'Authorization' => 'Bearer ' . $this->token,
                "Content-Type" => "application/json"
            ],
            'timeout' => 120,
            "body" => json_encode([
                "model" => "gpt-3.5-turbo",
                "messages" => array_reverse($contextMessageCollection->toArray())
            ], JSON_UNESCAPED_UNICODE)
        ]);


        $json = $result->getBody()->getContents();

        if(!$result = json_decode($json, true)) {
            throw new ExceptionGtpNoAnswer("Не корректный json");
        }

        if(empty($result['choices'][0]['message']['content'])) {
            throw new ExceptionGtpNoAnswer("Не корректные ключи");
        }

        return $result['choices'][0]['message']['content'];
    }
}