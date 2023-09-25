<?php

namespace App\Service\Chat\Template;

abstract class TemplateAbstract implements TemplateInterface
{
    abstract function getFields();
}