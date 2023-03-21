<?php

use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Utils\ApplicationContext;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;


if (!function_exists("success")) {
    /**
     * 操作成功返回的数据
     * @param string $msg 提示信息
     * @param mixed|null $data 要返回的数据
     * @param int $code 成功码默认为0
     */
    function success(mixed $data = null, string $msg = 'success', int $code = 0): Psr7ResponseInterface
    {
        $response = ApplicationContext::getContainer()->get(ResponseInterface::class);
        $result = [
            'error' => $code,
            'message' => $msg,
            'data' => $data,
            'timestamp' => time(),
        ];
        return $response->json($result);
    }
}

if (!function_exists("error")) {
    /**
     * 操作失败返回的数据
     * @param string $msg 提示信息
     * @throws Exception
     */
    function error(string $msg = '')
    {
        throw new \Exception($msg);
    }
}
