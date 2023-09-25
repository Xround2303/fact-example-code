<?php

namespace App\Service\Chat;

use App\Enum\EnumAppOption;
use App\Repository\Bitrix24Rest\RepositoryAppOption;
use App\Repository\Bitrix24Rest\RepositoryUserOption;
use App\Service\Gpt\EnumRole;

class ServiceRememberMessage
{
    public function __construct(
        protected RepositoryUserOption $repositoryUserOption
    ) {
    }

    public function save(int $messageId, $role = EnumRole::ROLE_USER)
    {
        $userMessageList = $this->fetchMessageList();

        if($this->isExistMessageId($messageId, $role, $userMessageList)) {
            return;
        }

        $userMessageList[$role][] = $messageId;

        $this->repositoryUserOption->add([
            EnumAppOption::CHAT_USER_MESSAGE_ID_LIST => json_encode($userMessageList, JSON_UNESCAPED_UNICODE)
        ]);
    }

    protected function fetchMessageList(): array
    {
        if($json = $this->repositoryUserOption->findByOption(EnumAppOption::CHAT_USER_MESSAGE_ID_LIST)) {
            $rows = json_decode($json, true);
        }

        return $rows ?? [];
    }

    protected function isExistMessageId(int $messageId, string $role, array $userMessageList = []): bool
    {
        return in_array($messageId, $userMessageList[$role] ?? []);
    }

    public function loadMessageIdByRole(string $role = EnumRole::ROLE_USER): array
    {
        if($rows = $this->fetchMessageList()) {
            return $rows[$role] ?? [];
        }

        return [];
    }
}