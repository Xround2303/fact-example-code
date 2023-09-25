<?php

namespace App\Service\Chat\Command;

use App\Dto\DtoCommand;

interface CommandInterface
{
    public function execute(DtoCommand $dto);

    public function getTitle();

    public function isGlobalCommand(): bool;
}


