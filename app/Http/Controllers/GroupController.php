<?php

namespace App\Http\Controllers;

use App\Services\GroupService;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    private $groupService;
    private $request;

    public function __construct(GroupService $groupService, Request $request)
    {
        $this->groupService = $groupService;
        $this->request = $request;
    }

    public function createGroup()
    {
        if (!$this->request->has('name')) {
            return $this->missingFieldResponse('name');
        }
        if (!$this->request->has('admin')) {
            return $this->missingFieldResponse('admin');
        }

        $group = $this->groupService->postGroup($this->request->all());

        return $this->successResponse(201, 'Group succesfully created', $group);
    }

    public function createGroupUser()
    {
        if (!$this->request->has('email')) {
            return $this->missingFieldResponse('email');
        }
        if (!$this->request->has('group_id')) {
            return $this->missingFieldResponse('group_id');
        }
        if (!$this->request->has('color')) {
            return $this->missingFieldResponse('color');
        }

        $this->groupService->postGroupUser($this->request->all());

        return $this->successResponse(201, 'User for group succesfully created');
    }

    public function changeGroupColor()
    {
        if (!$this->request->has('user_id')) {
            return $this->missingFieldResponse('user_id');
        }
        if (!$this->request->has('group_id')) {
            return $this->missingFieldResponse('group_id');
        }
        if (!$this->request->has('color')) {
            return $this->missingFieldResponse('color');
        }

        $this->groupService->postGroupColor($this->request->user_id, $this->request->group_id, $this->request->color);

        return $this->successResponse(201, 'Group color changed');
    }

    public function getGroup($group_id, $user_id)
    {
        $group = $this->groupService->getGroup($group_id, $user_id);

        return $this->successResponse(200, null, $group);
    }

    public function updateGroup($group_id)
    {
        if (!$this->request->has('user_id')) {
            return $this->missingFieldResponse('user_id');
        }
        if (!$this->request->has('name')) {
            return $this->missingFieldResponse('name');
        }
        if (!$this->request->has('admin')) {
            return $this->missingFieldResponse('admin');
        }

        if ($this->groupService->putGroup($this->request->user_id, $group_id, $this->request->all())) {
            return $this->successResponse(200, 'Group succesfully updated');
        }
        return $this->errorResponse(404, 'No group found with that ID');
    }

    public function updateGroupUser($group_user_id)
    {
        if (!$this->request->has('user_id')) {
            return $this->missingFieldResponse('user_id');
        }
        if (!$this->request->has('group_id')) {
            return $this->missingFieldResponse('group_id');
        }
        if (!$this->request->has('color')) {
            return $this->missingFieldResponse('color');
        }

        if ($this->groupService->putGroupUser($this->request->user_id, $group_user_id, $this->request->all())) {
            return $this->successResponse(200, 'Group user succesfully updated');
        }
        return $this->errorResponse(404, 'No group user found with that ID');
    }

    public function removeGroup($group_id, $admin_id)
    {
        if ($this->groupService->deleteGroup($group_id, $admin_id)) {
            return $this->successResponse(200, 'Group succesfully deleted');
        }
        return $this->errorResponse(404, 'No group found with that ID');
    }

    public function removeGroupUser($user_id)
    {
        if (!$this->request->has('group_id')) {
            return $this->missingFieldResponse('group_id');
        }

        if ($this->groupService->deleteGroupUser($this->request->group_id, $user_id)) {
            return $this->successResponse(200, 'Group user succesfully deleted');
        }
        return $this->errorResponse(404, 'No group user found with that ID');
    }
}
