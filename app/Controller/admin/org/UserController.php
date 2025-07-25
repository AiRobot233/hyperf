<?php

namespace App\Controller\admin\org;

use App\Middleware\AntiRepeatMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\LoginMiddleware;
use App\Request\UserRequest;
use App\Services\admin\org\UserService;
use App\Utils\Tool;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Http\Message\ResponseInterface;

#[Controller]
#[Middlewares([LoginMiddleware::class, AuthMiddleware::class])]
class UserController
{

    #[Inject]
    private UserService $userService;

    #[RequestMapping(path: "/admin/user", methods: "get")]
    public function list(RequestInterface $request): ResponseInterface
    {
        $size = $request->input('pageSize', 10);
        $name = $request->input('name', '');
        $roleId = $request->input('roleId', 0);
        $res = $this->userService->list($size, $name, $roleId);
        return Tool::OK($res);
    }

    #[Middlewares([AntiRepeatMiddleware::class])]
    #[RequestMapping(path: "/admin/user", methods: "post")]
    public function add(UserRequest $request): ResponseInterface
    {
        $data = $request->post();
        $this->userService->add($data);
        return Tool::OK();
    }

    #[RequestMapping(path: "/admin/user/{id}", methods: "put")]
    public function edit(UserRequest $request, int $id): ResponseInterface
    {
        $data = $request->getParsedBody();
        $this->userService->edit($id, $data);
        return Tool::OK();
    }

    #[RequestMapping(path: "/admin/user/{id}", methods: "delete")]
    public function del(int $id): ResponseInterface
    {
        $this->userService->del($id);
        return Tool::OK();
    }
}