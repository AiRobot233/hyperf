<?php

namespace App\Utils;

use Psr\Http\Message\ResponseInterface;

class Tool
{

    public static function E(string $msg): void
    {
        HttpUtil::getInstance()->error($msg);
    }

    public static function OK(mixed $data = null, string $msg = 'success', int $code = 0): ResponseInterface
    {
        return HttpUtil::getInstance()->success($data, $msg, $code);
    }
}