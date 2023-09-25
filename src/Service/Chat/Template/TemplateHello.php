<?php

namespace App\Service\Chat\Template;

use App\Service\Chat\Command\CommandChatSendAbout;
use App\Service\Chat\Command\CommandChatSendHelp;
use App\Service\Chat\Command\CommandChatStartTheme;
use App\Service\Chat\Template\Button\ButtonAbout;
use App\Service\Chat\Template\Button\ButtonHelp;
use App\Service\Chat\Template\Button\ButtonToggleTheme;

class TemplateHello extends TemplateAbstract
{
    public function __construct(
        protected int $botId,
        protected int $userId
    ) {
    }

    public function getFields()
    {
        return [
            'BOT_ID'=> $this->botId,
            'FROM_USER_ID '=>$this->botId,
            'TO_USER_ID'=>$this->userId,
            'DIALOG_ID'=>$this->userId,
            "MESSAGE" => "Привет! Я Чат-бот ChatGPT.",
            "ATTACH" => [
                [
                    "MESSAGE" => 'Выберите интересующую вас команду.'
                ],
            ],
            "KEYBOARD" => [
                (new ButtonHelp())->getFields(),
                (new ButtonAbout())->getFields(),
                (new ButtonToggleTheme())->getFields(),
            ]
        ];
    }
}