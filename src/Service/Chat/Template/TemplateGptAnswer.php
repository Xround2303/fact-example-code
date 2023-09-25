<?php

namespace App\Service\Chat\Template;

use App\Service\Chat\Template\Button\ButtonBalance;
use App\Service\Chat\Template\Button\ButtonHelp;
use App\Service\Chat\Template\Button\ButtonToggleTheme;

class TemplateGptAnswer extends TemplateAbstract
{
    public function __construct(
        protected int $dialogId,
        protected string $message,
        protected int $usedToken = 0,
    ) {
    }

    public function getFields(): array
    {
        return [
            "DIALOG_ID" => $this->dialogId,
            "MESSAGE" =>  $this->message,
            "ATTACH" => [
                [
                    "MESSAGE" => "Использовано токенов: " . $this->usedToken,
                ]
            ],
            "KEYBOARD" => [
                (new ButtonHelp())->getFields(),
                (new ButtonBalance())->getFields(),
                (new ButtonToggleTheme())->getFields(),
            ]
        ];
    }

    /**
     * @return int
     */
    public function getDialogId(): int
    {
        return $this->dialogId;
    }

    /**
     * @param int $dialogId
     * @return TemplateGptAnswer
     */
    public function setDialogId(int $dialogId): TemplateGptAnswer
    {
        $this->dialogId = $dialogId;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return TemplateGptAnswer
     */
    public function setMessage(string $message): TemplateGptAnswer
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return bool
     */
    public function isChatThemeActive(): bool
    {
        return $this->isChatThemeActive;
    }

    /**
     * @param bool $isChatThemeActive
     * @return TemplateGptAnswer
     */
    public function setIsChatThemeActive(bool $isChatThemeActive): TemplateGptAnswer
    {
        $this->isChatThemeActive = $isChatThemeActive;
        return $this;
    }


}