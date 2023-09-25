<?php

declare(strict_types=1);

namespace App;

use App\Provider\ProviderManagerPortalUser;
use Exception;
use Laminas\Diactoros\ResponseFactory;
use League\Container\ReflectionContainer;
use League\Route\Router;
use League\Route\Strategy\JsonStrategy;
use League\Route\Strategy\StrategyAwareInterface;

final class RouterFacade
{
    private static ?self $instance = null;
    protected StrategyAwareInterface $router;
    protected DIContainer $container;

    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * is not allowed to call from outside to prevent from creating multiple instances,
     * to use the singleton, you have to obtain the instance from Singleton::getInstance() instead
     */
    private function __construct()
    {
        $responseFactory = new ResponseFactory();

        $this->container = new DIContainer(new ReflectionContainer());
        $this->container->addServiceProvider(new Provider\ServiceProvider);
        $this->container->addServiceProvider(new Provider\ProviderBitrix24Client);
        $this->container->addServiceProvider(new Provider\ProviderCommands);
        $this->container->addServiceProvider(new Provider\ProviderGptClient);
        $this->container->addServiceProvider(new ProviderManagerPortalUser);

        $strategy = new JsonStrategy($responseFactory);
        $strategy->setContainer($this->container);

        $this->router  = (new Router)->setStrategy($strategy);
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

    /**
     * @return StrategyAwareInterface
     */
    public function getRouter(): StrategyAwareInterface
    {
        return $this->router;
    }

    /**
     * @param StrategyAwareInterface $router
     * @return RouterFacade
     */
    public function setRouter(StrategyAwareInterface $router): RouterFacade
    {
        $this->router = $router;
        return $this;
    }

    public function getPathRoute($name)
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] .
            $this->router->getNamedRoute($name)->getPath();
    }

    /**
     * @return DIContainer
     */
    public function getContainer(): DIContainer
    {
        return $this->container;
    }




}