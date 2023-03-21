<?php

namespace App\Controller\admin\org;

use App\Middleware\LoginMiddleware;
use App\Services\admin\org\SubService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function App\Controller\admin\array_key_first;

#[Controller]
#[Middleware(LoginMiddleware::class)]
class SubController
{

    #[Inject]
    private SubService $subService;

    #[RequestMapping(path: "/admin/subassembly", methods: "post")]
    public function subassembly(RequestInterface $request): ResponseInterface
    {
        $data = $request->post();
        $key = array_key_first($data);
        $res = $this->subService->common($key, $data[$key]);
        return success($res);
    }
}