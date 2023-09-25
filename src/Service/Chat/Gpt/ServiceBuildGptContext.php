<?php

namespace App\Service\Chat\Gpt;


use App\Enum\EnumAppConfig;
use App\Enum\EnumAppOption;
use App\Manager\ManagerPortalUser;
use App\Repository\Bitrix24Rest\RepositoryAppOption;
use App\Repository\Bitrix24Rest\RepositoryChatMessage;
use App\Service\Chat\ServiceRememberMessage;
use App\Service\Gpt\ContextMessage;
use App\Service\Gpt\ContextMessageCollection;
use App\Service\Gpt\EnumRole;
use Gioni06\Gpt3Tokenizer\Gpt3Tokenizer;

class ServiceBuildGptContext
{
    protected array $rememberUserMessageIdList = [];
    protected array $rememberAssistantMessageIdList = [];

    public function __construct(
        protected RepositoryAppOption $repositoryAppOption,
        protected ServiceRememberMessage $serviceRememberMessage,
        protected Gpt3Tokenizer $gpt3Tokenizer,
        protected ContextMessageCollection $contextMessageCollection,
        protected RepositoryChatMessage $repositoryChatMessage
    ){
    }

    public function build($dialogId): ContextMessageCollection
    {
        $messageList = $this->repositoryChatMessage->findByDialogId($dialogId, 500);

        $this->rememberUserMessageIdList = $this->serviceRememberMessage
            ->loadMessageIdByRole(EnumRole::ROLE_USER);

        $this->rememberAssistantMessageIdList = $this->serviceRememberMessage
            ->loadMessageIdByRole(EnumRole::ROLE_ASSISTANT);

        foreach ($messageList as $message) {
            if(
                $this->getMessageRole($message['id']) === EnumRole::ROLE_USER ||
                $this->getMessageRole($message['id']) === EnumRole::ROLE_ASSISTANT
            ) {
                $this->contextMessageCollection->push(
                    new ContextMessage(
                        $message['text'],
                        $this->getMessageRole($message['id']),
                        $this->gpt3Tokenizer->count($message['text']),
                        strtotime($message['date']),
                        $message['id'],
                        $message['author_id']
                    )
                );
            }
        }

        return $this->filterContext($this->contextMessageCollection);
    }

    protected function getMessageRole(int $messageId): string
    {
        if(in_array($messageId, $this->rememberUserMessageIdList)) {
            return EnumRole::ROLE_USER;
        }

        if(in_array($messageId, $this->rememberAssistantMessageIdList)) {
            return EnumRole::ROLE_ASSISTANT;
        }

        return EnumRole::ROLE_SYSTEM;
    }

    protected function isChatThemeActive(): bool
    {
        return ManagerPortalUser::getInstance()->isChatThemeActive();
    }

    protected function filterMessageListRelativeToActiveTheme(ContextMessageCollection $context): ContextMessageCollection
    {
        if($this->isChatThemeActive()) {
            return $this->filterMessageListTheme($context);
        }

        return $this->filterLastMessageUser($context);
    }

    protected function filterLastMessageUser(ContextMessageCollection $context): ContextMessageCollection
    {
        foreach ($context->getList() as $i => $message) {
            if($message->role === EnumRole::ROLE_USER) {
                $lastUserMessage = $message;
                break;
            }
        }

        foreach ($context->getList() as $i => $message) {
            $context->deleteByIndex($i);
        }

        if(!empty($lastUserMessage)) {
            return $context->push($lastUserMessage);
        }

        return $context;
    }

    protected function filterMessageListTheme(ContextMessageCollection $context): ContextMessageCollection
    {
        foreach ($context->getList() as $i => $message) {
            if($message->unixtime < ManagerPortalUser::getInstance()->getChatThemeStartTime()) {
                $context->deleteByIndex($i);
            }
        }

        return $context;
    }

    protected function filterContext(ContextMessageCollection $context): ContextMessageCollection
    {
        $c1 = $this->filterMessageListRelativeToActiveTheme($context);
        return $this->filterMessageListRelativeGptTokenLimit($c1);
    }

    protected function filterMessageListRelativeGptTokenLimit(ContextMessageCollection $context): ContextMessageCollection
    {
        $counter = 0;

        foreach ($context->getList() as $message) {
            $counter += $message->getCountToken();
        }

        if($counter > EnumAppConfig::CHAT_CONTEXT_TOKEN_LIMIT) {
            unset($context->getList()[count($context->getList()) - 1]);
            return $this->filterMessageListRelativeGptTokenLimit($context);
        }

        return $context;
    }
}