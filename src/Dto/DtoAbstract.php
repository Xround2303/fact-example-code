<?php

namespace App\Dto;

use Psr\Http\Message\ServerRequestInterface;

abstract class DtoAbstract implements \App\Interfaces\InterfaceDto
{
    public static function fromRequest(ServerRequestInterface $request)
    {
    }
}