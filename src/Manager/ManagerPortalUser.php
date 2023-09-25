<?php

namespace App\Manager;

use App\Enum\EnumAppOption;
use App\Repository\Bitrix24Rest\RepositoryAppOption;
use App\Repository\Bitrix24Rest\RepositoryUserOption;
use App\RouterFacade;
use Exception;

class ManagerPortalUser
{
    private static ?self $instance = null;
    protected int $chatThemeStartTime = 0;

    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = RouterFacade::getInstance()->getContainer()->get(self::class);
        }

        return self::$instance;
    }

    /**
     * is not allowed to call from outside to prevent from creating multiple instances,
     * to use the singleton, you have to obtain the instance from Singleton::getInstance() instead
     */
    public function __construct(protected RepositoryUserOption $repositoryUserOption) {
        $this->chatThemeStartTime = $this->repositoryUserOption->findByOption(
            EnumAppOption::CHAT_THEME_START_TIME
        ) ?? 0;
    }

    /**
     * prevent the instance from being cloned (which would create a second instance of it)
     */
    private function __clone()
    {
    }

    /**
     * prevent from being unserialized (which would create a second instance of it)
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }

    public function isChatThemeActive(): bool
    {
        return !empty($this->chatThemeStartTime);
    }

    public function getChatThemeStartTime(): int
    {
        return $this->chatThemeStartTime;
    }
}