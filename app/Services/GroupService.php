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
        return $this->model->create($group);
    }

    public function postGroupUser(array $group)
    {
        $user = User::where('email', $group['email'])->first();

        $groupUser = new GroupUser();
        $groupUser->create([
            'user_id' => $user->id,
            'group_id' => $group['group_id'],
            'color' => $group['color']
        ]);
    }

    public function postGroupColor(int $user_id, int $group_id, string $color)
    {
        $user = User::find($user_id);
        $group = $user->groups->where('id', $group_id)->first();
        $users = $group->users;
        foreach ($users as $user) {
            if ($user->pivot->user_id === $user_id) {
                DB::table('group_user')->where('group_id', $group_id)->update(['color' => $color]);
                return true;
            }
        }
        return false;
    }

    public function getGroup(int $group_id, int $user_id)
    {
        $user = User::find($user_id);
        $group = $user->groups->where('id', $group_id)->first();
        $users = $group->users;
        $tmpUsers = [];
        foreach ($users as $user) {
            array_push($tmpUsers, [
                'user_id' => $user->pivot->user_id,
                'name' => $user->name,
                'color' => $user->pivot->color
            ]);
        }
        $tmpGroup = [
            'name' => $group->name,
            'admin' => $group->admin,
            'users' => $tmpUsers
        ];
        return $tmpGroup;
    }

    public function putGroup(int $user_id, int $id, array $group)
    {
        $user = User::find($user_id);
        $groupToUpdate = $user->groups->find($id);

        if (!is_null($groupToUpdate)) {
            $groupToUpdate->update($group);
            return true;
        }
        return false;
    }

    public function putGroupUser(int $user_id, int $group_id, array $group)
    {
        $groupToUpdate = GroupUser::find($group_id)
            ->where('id', $group_id)
            ->where('user_id', $user_id);

        if (!is_null($groupToUpdate)) {
            $groupToUpdate->update($group);
            return true;
        }
        return false;
    }

    public function deleteGroup(int $group_id, int $admin_id)
    {
        $group = Group::find($group_id);

        if (!is_null($group) && $group->admin === $admin_id) {
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
