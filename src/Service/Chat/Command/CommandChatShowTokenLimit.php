<?php

namespace App\Service\Chat\Command;


use App\Dto\DtoCommand;
use App\Enum\EnumAppOption;
use App\Manager\ManagerPortal;
use App\Service\Chat\Template\TemplateSystemMessage;
use App\Service\Chat\Template\TemplateUserMessage;


class CommandChatShowTokenLimit extends CommandChatAbstract
{
    const COMMAND_NAME = "balance";

    public function execute(DtoCommand $dto)
    {
        if(ManagerPortal::getInstance()->isTokenUserMode()) {
            return $this->serviceSendMessage->execute(
                new TemplateSystemMessage($dto->dialogId, "Статистика использования токенов для вашего портала не ведется, т.к. вы используете свой ключ.")
            );
        }

        $modelPortal = $this->repositoryPortal->getActivePortal(
            $this->bitrix24Client->getClient()->getMemberId()
        );

        $modelTokenLimits = $modelPortal->tokenLimits;

        return $this->serviceSendMessage->execute(
            new TemplateUserMessage($dto->dialogId,
                sprintf('Использовано токенов: [B]%s[/B] из [B]%s[/B]. Доступно: [B]%s[/B] токенов.[BR] Для добавления токенов вам нужно перейти в [URL=%s]настройки приложения[/URL].',
                    $this->formatNumber($modelPortal->getTotalCountTokenUsage()),
                    $this->formatNumber($modelTokenLimits->limit),
                    $this->formatNumber($this->calcAvailableToken($modelTokenLimits->limit, $modelPortal->getTotalCountTokenUsage())),
                    $this->genUrlToSettingPage()
                )
            )
        );
    }

    protected function genUrlToSettingPage(): string
    {
        return $this->bitrix24Client->getClient()->getDomain() . sprintf(
                "/marketplace/app/%s/",
                ManagerPortal::getInstance()->getAppId()
            );
    }

    protected function formatNumber(int $number): string
    {
        return number_format($number, 0, ' ', ' ');
    }

    protected function calcAvailableToken(int $total, int $usages): int
    {
        return $total - $usages;
    }

    public function getTitle()
    {
        return "Узнать баланс токенов";
    }

    public function isGlobalCommand(): bool
    {
        return true;
    }
}