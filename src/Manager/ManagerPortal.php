<?php

namespace App\Manager;

use App\Enum\EnumAppOption;
use App\Repository\Bitrix24Rest\RepositoryAppOption;
use App\RouterFacade;
use Exception;

class ManagerPortal
{
    private static ?self $instance = null;
    protected bool $tokenUserMode = false;
    protected ?string $tokenUser;
    /**
     * @var false|mixed
     */
    protected mixed $modelAppInfo;

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
    public function __construct(
        protected RepositoryAppOption $repositoryAppOption
    ) {
        $settings = $this->repositoryAppOption->all();
        $this->modelAppInfo = $this->repositoryAppOption->findAppInfo();

        $this->tokenUserMode = isset($settings[EnumAppOption::SERVICE_TOKEN_MODE]) ? (bool)$settings[EnumAppOption::SERVICE_TOKEN_MODE] : false;
        $this->tokenUser = isset($settings[EnumAppOption::SERVICE_USER_TOKEN]) ? $settings[EnumAppOption::SERVICE_USER_TOKEN] : "";
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

    public function isTokenUserMode(): bool
    {
        return !empty($this->tokenUserMode) && !empty($this->tokenUser);
    }

    public function getAppId(): ?int
    {
        return $this->modelAppInfo->id;
    }

    public function getUserToken()
    {
        return $this->tokenUser;
    }
}