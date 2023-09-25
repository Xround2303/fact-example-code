<?php

namespace App;

use League\Container\Definition\DefinitionAggregateInterface;
use League\Container\Inflector\InflectorAggregateInterface;
use League\Container\ServiceProvider\ServiceProviderAggregateInterface;

class DIContainer extends \League\Container\Container
{

    public function __construct(
        \League\Container\ReflectionContainer $reflectionContainer,
        DefinitionAggregateInterface $definitions = null,
        ServiceProviderAggregateInterface $providers = null,
        InflectorAggregateInterface $inflectors = null
    ) {

        parent::__construct($definitions, $providers, $inflectors);

        $this->delegate($reflectionContainer);
    }
}