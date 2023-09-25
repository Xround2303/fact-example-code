<?php

namespace App\Service\Chat\Prompt;

use App\Dto\DtoGptAnswer;
use App\Dto\DtoPrompt;
use App\Enum\EnumAppOption;
use App\Exception\ExceptionApplication;
use App\Exception\ExceptionGtpNoAnswer;
use App\Exception\ExceptionPortalLimitHasEnded;
use App\Model\ModelGptModel;
use App\Model\ModelPortal;
use App\Repository\Bitrix24Rest\RepositoryAppOption;
use App\Repository\Bitrix24Rest\RepositoryChatMessage;
use App\Repository\Database\RepositoryPortal;
use App\Repository\Database\RepositoryPortalTokenLimit;
use App\Repository\Database\RepositoryPortalTokenUsage;
use App\Service\Chat\Gpt\ServiceRequestGptAnswer;
use App\Service\Chat\Gpt\ServiceBuildGptContext;
use App\Service\Chat\ServiceBuildTemplateGptAnswer;
use App\Service\Chat\ServiceMessageUpdateTemplate;
use App\Service\Chat\ServiceRememberActiveHandlerPromptQuest;
use App\Service\Chat\ServiceRememberMessage;
use App\Service\Chat\ServiceSendMessage;
use App\Service\Chat\Template\TemplateSystemMessage;
use App\Service\Gpt\ContextMessageCollection;
use App\Service\Gpt\EnumRole;
use GuzzleHttp\Exception\GuzzleException;
use Sw24\Bitrix24Auth\Bitrix24Client;

abstract class PromptQuestAbstract extends PromptAbstract
{
    protected ModelPortal $modelPortal;

    public function __construct(
        protected ServiceRememberMessage $serviceRememberMessage,
        protected ServiceBuildTemplateGptAnswer $serviceBuildTemplateGptAnswer,
        protected ServiceSendMessage $serviceSendMessage,
        protected ServiceRememberActiveHandlerPromptQuest $serviceRememberActiveHandlerPromptQuest,
        protected ServiceBuildGptContext $serviceBuildGptContext,
        protected ServiceRequestGptAnswer $serviceRequestGptAnswer,
        protected RepositoryChatMessage $repositoryChatMessage,
        protected RepositoryAppOption $repositoryAppOption,
        protected RepositoryPortalTokenLimit $repositoryPortalTokenLimit,
        protected RepositoryPortalTokenUsage $repositoryPortalTokenUsage,
        protected RepositoryPortal $repositoryPortal,
        protected Bitrix24Client $bitrix24Client
    ) {
    }

    public function isHandlerActive(): bool
    {
        return $this->serviceRememberActiveHandlerPromptQuest->isHandleActive();
    }

    protected function updateHandlerActive(bool $value)
    {
        return $this->serviceRememberActiveHandlerPromptQuest->update($value);
    }

    /**
     * @throws \Bitrix24\Exceptions\Bitrix24Exception
     * @throws ExceptionApplication
     */
    public function execute(DtoPrompt $dto)
    {
        // Проверить конец предыдущего ответа
        if($this->isHandlerActive() AND $_ENV['MODE'] !== "dev") {
            return $this->serviceSendMessage->execute(
                new TemplateSystemMessage(
                    $dto->dialogId,
                    "Предыдущий запрос еще не обработан"
                )
            );
        }

        // Начало генерации ответа
        $this->updateHandlerActive(1);

        // Получаем портал
        $this->modelPortal = $this->repositoryPortal->getActivePortal(
            $this->bitrix24Client->getClient()->getMemberId()
        );

        // Сервис по сохранению добавленного сообщения
        $this->serviceRememberMessage->save($dto->messageId);

        try {
            $context = $this->getContext($dto->toUserId);

            if($this->checkUserTokenLimit($context)) {
                throw new ExceptionApplication(sprintf(
                    "Не хватает токенов для запроса, осталось %s/%s, требуется: %s",
                    $this->modelPortal->getTotalCountTokenUsage(),
                    $this->modelPortal->tokenLimits->limit,
                    $context->getTotalToken()
                ));
            }

            $dtoGptAnswer = $this->getGptAnswer($context);

            $assistantMessageId = $this->sendAssistantMessage($dtoGptAnswer, $dto->dialogId);

            $this->rememberAssistantMessageId($assistantMessageId);

            $this->saveRequestTokenUsage(
                $dto,
                $dtoGptAnswer->tokenCountMessage,
                $context->getTotalToken(),
                $assistantMessageId
            );

        } catch (ExceptionApplication $e) {
            $this->serviceSendMessage->execute(
                new TemplateSystemMessage(
                    $dto->dialogId,
                    $e->getMessage()
                )
            );
        } catch (ExceptionGtpNoAnswer $e) {
        } catch (GuzzleException $e) {
            $this->serviceSendMessage->execute(
                new TemplateSystemMessage(
                    $dto->dialogId,
                    "Время ожидания запроса истекло, пожалуйста, повторите запрос"
                )
            );
        }

        // Конец генерации ответа
        $this->updateHandlerActive(0);
    }

    protected function getContext(int $dialogId): ContextMessageCollection
    {
        return $this->serviceBuildGptContext
            ->build($dialogId);
    }

    protected function checkUserTokenLimit(ContextMessageCollection $context): bool
    {
        return $this->modelPortal->tokenLimits->limit < $this->modelPortal->getTotalCountTokenUsage() + $context->getTotalToken();
    }

    protected function getGptAnswer(ContextMessageCollection $context): DtoGptAnswer
    {
        return $this->serviceRequestGptAnswer
            ->build($context);
    }

    protected function sendAssistantMessage(DtoGptAnswer $dtoGptAnswer, $dialogId)
    {
        return $this->serviceSendMessage->execute(
            $this->serviceBuildTemplateGptAnswer
                ->build($dtoGptAnswer, $dialogId)
        );
    }

    protected function rememberAssistantMessageId(int $assistantMessageId)
    {
        $this->serviceRememberMessage->save($assistantMessageId, EnumRole::ROLE_ASSISTANT);
    }

    protected function saveRequestTokenUsage(DtoPrompt $dto, int $tokenCountCompletion, int $tokenCountPrompt, int $assistantMessageId)
    {
        $this->repositoryPortalTokenUsage->create([
            "user_id" => $dto->userId,
            "portal_id" => $this->modelPortal->id,
            "token_count_prompt" => $tokenCountPrompt,
            "token_count_completion" => $tokenCountCompletion,
            "message_id" => $assistantMessageId
        ]);
    }
}