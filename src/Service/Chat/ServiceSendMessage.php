<?php

namespace App\Service\Chat;

use App\Repository\Bitrix24Rest\RepositoryChatMessage;
use App\Service\Chat\Template\TemplateInterface;

class ServiceSendMessage
{
    public function __construct(
        protected RepositoryChatMessage $repositoryChatMessage
    ) {
    }

    public function execute(TemplateInterface $template)
    {
        return $this->repositoryChatMessage->add($template->getFields());
    }
}