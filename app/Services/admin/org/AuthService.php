<?php

namespace App\Services\admin\org;

use App\Model\Role;
use App\Model\Rule;
use App\Model\User;
use App\Utils\Tool;
use App\Utils\Util;
use Hyperf\Context\Context;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class AuthService
{
    #[Inject]
    private Util $util;

    public function auth()
    {
        $user = Context::get('userData');
        $role = Role::query()->where('id', $user['role_id'])->first(['rule', 'is_system']);
        if (empty($role)) {
            Tool::E('角色不存在!');
        }
        if ($role->is_system == 1) {
            $rules = explode(',', $role->rule);
            $rule = Rule::query()->where('type', 'page')->whereIn('id', $rules)->get();
            $roles = Db::select("SELECT b.router,a.operation FROM rule AS b LEFT JOIN (SELECT pid,GROUP_CONCAT(tag) AS operation FROM `rule` WHERE type = 'api' AND id IN ({$role->rule}) GROUP BY pid) AS a ON a.pid = b.id WHERE a.operation IS NOT NULL");
        } else {
            $rule = Rule::query()->where('type', 'page')->get();
            $roles = Db::select("SELECT b.router,a.operation FROM rule AS b LEFT JOIN (SELECT pid,GROUP_CONCAT(tag) AS operation FROM `rule` WHERE type = 'api' GROUP BY pid) AS a ON a.pid = b.id WHERE a.operation IS NOT NULL");
        }
        $tree = $this->util->arrayToTree($rule->toArray());
        return ['routes' => $tree, 'roles' => $roles];
    }

    public function changePwd(string $password, string $oldPassword)
    {
        $user = Context::get('userData');
        $data = User::query()->where('id', $user['id'])->first();
        if (empty($data)) {
            Tool::E('用户不存在!');
        }
        $md5 = md5($oldPassword . $data->salt);
        if ($data->password != $md5) {
            Tool::E('旧密码错误！');
        }
        $p = md5($password . $data->salt);
        $data->password = $p;
        $data->save();
    }

    public function firstPwd(string $password)
    {
        $user = Context::get('userData');
        $data = User::query()->where('id', $user['id'])->first();
        if (empty($data)) {
            Tool::E('用户不存在!');
        }
        if ($data->first_login != 1) {
            Tool::E('无需修改密码！');
        }
        $p = md5($password . $data->salt);
        $data->password = $p;
        $data->first_login = 2;
        $data->save();
    }
}
