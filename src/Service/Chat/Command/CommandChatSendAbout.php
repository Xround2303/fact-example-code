<?php

namespace App\Service\Chat\Command;


use App\Dto\DtoCommand;
use App\Service\Chat\Template\TemplateAbout;
use App\Service\Chat\Template\TemplateUserMessage;

class CommandChatSendAbout  extends CommandChatAbstract
{
    const COMMAND_NAME = "about";

    public function execute(DtoCommand $dto)
    {
        $this->serviceSendMessage->execute(
            new TemplateAbout($dto->dialogId,
                sprintf('Меня разработали специалисты из компании [B]w[/B].[BR]Я интегрирован с продуктом компании [B]OpenAI ChatGPT[/b], на основе языковой модели [B]gpt-3.5-turbo[/B].[BR][BR]Список всех приложений СкайВеб24 вы можете посмотреть в [URL=https://www.bitrix24.ru/apps/?partner_id=981093&search=Y]Битрикс24.Маркет[/URL].')
            )
        );
    }

    public function getTitle()
    {
        return "Обо мне";
    }

    public function isGlobalCommand(): bool
    {
        return true;
    }
}