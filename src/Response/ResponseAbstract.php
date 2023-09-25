<?php

namespace App\Response;

use App\Enum\EnumResponseStatus;

abstract class ResponseAbstract
{
    public static function toArray(mixed $content, $status = EnumResponseStatus::STATUS_SUCCESS, $code = 200): array
    {
        return [
            "code" => $code,
            "status" => $status,
            "result" => $content
        ];
    }
}