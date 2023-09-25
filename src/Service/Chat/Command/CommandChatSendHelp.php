<?php

namespace App\Service\Chat\Command;

use App\Dto\DtoCommand;
use App\Service\Chat\Template\TemplateHelp;
use App\Service\Chat\Template\TemplateUserMessage;

class CommandChatSendHelp extends CommandChatAbstract
{
    const COMMAND_NAME = "help";

    public function execute(DtoCommand $dto)
    {
        $this->serviceSendMessage->execute(
            new TemplateHelp($dto->dialogId,
                sprintf(
                '[B]Общая информация[/B][BR]Вы можете общаться с Чат-ботом, как с живым собеседником, задавая вопросы на любом языке. Обратите внимание, Чат-бот обладает ограниченными знаниями о событиях после 2021 года.[BR][BR][B]Режим «Одиночные сообщения»[/B][BR]Чтобы получить текстовый ответ, просто напишите в чат ваш вопрос или произвольный текст.[BR][BR][B]Режим «История переписки»[/B][BR]Для учета контекста предыдущих сообщений запустите команду [PUT=/%s]Начать тему[/PUT], тогда Чат-бот будет учитывать все предыдущие сообщения в рамках темы.[BR]Чтобы закончить тему и вернуться в простой режим, запустите команду [PUT=/%s]Закрыть тему[/PUT].[BR]При этом каждый запрос (связка вопросов-ответов) не должен превышать 4096 токенов. Проверка происходит на стороне Чат-бота перед отправкой истории сообщений, если она превышает лимит, то более ранние сообщения истории исключаются.[BR][BR][B]Токены[/B][BR]Токен – это комбинация символов, присутствующих в тексте. 1 токен равен примерно 4 символам английского алфавита или 1 символу русского алфавита. В случае превышения максимального объема токенов Чат-бот отправляет пользователю сообщение о том, что нужно сократить запрос.[BR][BR][B]Пользовательское соглашение[/B][BR]Все запросы Чат-боту не должны нарушать законодательство РФ и фильтр OpenAI, в противном случае работа Чат-бота на вашем портале будет автоматически заблокирована.[BR]Пользуясь Чат-ботом вы принимаете все условия [URL=https://test.ru/documents/marketplace-b24-license-agreement/?app=test.chatgpt]Лицензионного соглашения с конечным Пользователем[/URL], включая [URL=https://.ru/documents/marketplace-b24-license-agreement/chatgpt.php]Дополнительное Пользовательское соглашение[/URL].[BR][BR][B]Учет токенов[/B][BR]В настройках приложения вы можете выбрать 2 варианта работы:[BR]1. Свой ключ OpenAI ChatGPT, вы можете получить его в личном кабинете сервиса. В таком случае, вы будете самостоятельно пополнять баланс в личном кабинете OpenAI.[BR]2. Использовать наш ключ OpenAI ChatGPT. В таком случае, все токены будут учитываться в статистике и вы сможете докупать их у нас.
                ',
                    CommandChatStartTheme::COMMAND_NAME,
                    CommandChatEndTheme::COMMAND_NAME
                )
            )
        );
    }

    public function getTitle()
    {
        return "Помощь";
    }

    public function isGlobalCommand(): bool
    {
        return true;
    }
}