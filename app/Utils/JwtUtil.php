<?php

namespace App\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtUtil
{
    const key = '!Q@W#E$R5t';

    /**
     * 签发（默认缓存1天）
     * @param array $body 附加额外数据
     * @param int $expire 过期时间（秒）
     * @return array
     */
    public function issue(array $body = [], int $expire = 86400): array
    {
        $nowTime = time();
        // 过期时间
        $timestamp = $nowTime + $expire;
        $payload = [
            // 签发时间
            'iat' => $nowTime,
            'exp' => $timestamp,
            'expire' => $expire,
            'body' => $body
        ];
        $token = JWT::encode($payload, self::key, 'HS256'); //输出Token
        $time = date('Y-m-d H:i:s', $timestamp);
        return [
            'token' => $token,
            'time' => $time
        ];
    }

    /**
     * 验证
     * @param string $jwt
     * @return array|void
     * @throws \Exception
     */
    public function decode(string $jwt)
    {
        try {
            JWT::$leeway = 60;//当前时间减去60，把时间留点余地
            $decoded = JWT::decode($jwt, new Key(self::key, 'HS256')); //HS256方式，这里要和签发的时候对应
            return (array)$decoded->body;
        } catch (\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
            error('签名不正确');
        } catch (\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
            error('签名使用时间未到');
        } catch (\Firebase\JWT\ExpiredException $e) {  // token过期
            error('token过期');
        } catch (\Exception $e) {  //其他错误
            error('token其它错误');
        }
    }
}