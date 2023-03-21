<?php

namespace App\Services\admin\org;

use App\Model\User;
use App\Utils\JwtUtil;
use Hyperf\Di\Annotation\Inject;

class LoginService
{
    #[Inject]
    private JwtUtil $jwtUtil;

    public function login(string $name, string $password): array
    {
        $user = User::query()->where('name', $name)->first();
        if (empty($user)) error('用户不存在！');
        if ($user->status == 2) error('用户已被禁用！');
        $p = md5($password . $user->salt);
        if ($user->password != $p) error('密码错误！');
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone,
            'role_id' => $user->role_id
        ];
        return ['user' => $data, 'token' => $this->jwtUtil->issue($data)];
    }
}