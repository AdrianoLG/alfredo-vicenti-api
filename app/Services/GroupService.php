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

    public function postGroupUser(array $group)
    {
        $groupUser = new GroupUser();

        $groupUser->create($group);
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

    public function putGroup(int $user_id, int $id, array $group)
    {
        $user = User::find($user_id);
        $groupToUpdate =  $user->groups->find($id);

        if (!is_null($groupToUpdate)) {
            $groupToUpdate->update($group);
            return true;
        }
        return false;
    }

    public function putGroupUser(int $user_id, int $id, array $group)
    {
        $groupToUpdate = GroupUser::find($id)
            ->where('id', $id)
            ->where('user_id', $user_id);

        if (!is_null($groupToUpdate)) {
            $groupToUpdate->update($group);
            return true;
        }
        return false;
    }

    public function deleteGroup(int $admin, int $id)
    {
        $group = Group::find($id)->where('admin', $admin);

        if (!is_null($group)) {
            $group->delete();
            return true;
        }
        return false;
    }

    public function deleteGroupUser(int $group_id, int $user_id)
    {
        $group = GroupUser::where('user_id', $user_id)
            ->where('group_id', $group_id);

        if (!is_null($group)) {
            $group->delete();
            return true;
        }
        return false;
    }
}
