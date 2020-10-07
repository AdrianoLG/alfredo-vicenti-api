<?php

namespace App\Services;

use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GroupService
{
    private $model;

    public function __construct()
    {
        $this->model = new Group();
    }

    public function postGroup(array $group)
    {
        $this->model->create($group);
    }

    public function postGroupUser(int $user_id, int $group_id, string $color)
    {
        $groupUser = new GroupUser();

        $groupUser->create([
            'user_id' => $user_id,
            'group_id' => $group_id,
            'color' => $color
        ]);
    }

    public function getGroups(int $user_id)
    {
        $user = User::find($user_id);
        return $user->groups;
    }

    public function getGroup(int $user_id, int $id)
    {
        $user = User::find($user_id);
        return $user->groups->find($id);
    }
}
