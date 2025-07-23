<?php

namespace App\Controller\admin\org;

use App\Middleware\LoginMiddleware;
use App\Services\admin\org\AuthService;
use App\Utils\Tool;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

#[Controller]
#[Middleware(LoginMiddleware::class)]
class AuthController
{
    #[Inject]
    private AuthService $authService;

    #[RequestMapping(path: '/admin/routes', methods: 'get')]
    public function auth(): ResponseInterface
    {
        return Tool::OK($this->authService->auth());
    }

    #[RequestMapping(path: '/change/pwd', methods: 'put')]
    public function changePwd(RequestInterface $request): ResponseInterface
    {
        $password = $request->input('password');
        $oldPassword = $request->input('oldPassword');
        $this->authService->changePwd($password, $oldPassword);
        return Tool::OK();
    }

    #[RequestMapping(path: '/first/pwd', methods: 'put')]
    public function firstPwd(RequestInterface $request): ResponseInterface
    {
        $password = $request->input('password');
        $this->authService->firstPwd($password);
        return Tool::OK();
    }
}
