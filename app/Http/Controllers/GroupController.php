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

        $this->groupService->postGroup($this->request->all());

        return $this->successResponse(201, 'Group succesfully created');
    }

    public function createGroupUser()
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

        $this->groupService->postGroupUser($this->request->all());

        return $this->successResponse(201, 'User for group succesfully created');
    }

    public function getGroups()
    {
        if (!$this->request->has('user_id')) {
            return $this->missingFieldResponse('user_id');
        }

        $groups = $this->groupService->getGroups($this->request->user_id);

        return $this->successResponse(200, null, $groups);
    }

    public function getGroup($id)
    {
        if (!$this->request->has('user_id')) {
            return $this->missingFieldResponse('user_id');
        }

        $group = $this->groupService->getGroup($this->request->user_id, $id);

        return $this->successResponse(200, null, $group);
    }

    public function updateGroup($id)
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

        if ($this->groupService->putGroup($this->request->user_id, $id, $this->request->all())) {
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

    public function removeGroup($id)
    {
        if (!$this->request->has('admin')) {
            return $this->missingFieldResponse('admin');
        }

        if ($this->groupService->deleteGroup($this->request->admin, $id)) {
            return $this->successResponse(200, 'Group succesfully deleted');
        }
        return $this->errorResponse(404, 'No group found with that ID');
    }

    public function removeGroupUser($id)
    {
        if (!$this->request->has('group_id')) {
            return $this->missingFieldResponse('group_id');
        }

        if ($this->groupService->deleteGroupUser($this->request->group_id, $id)) {
            return $this->successResponse(200, 'Group user succesfully deleted');
        }
        return $this->errorResponse(404, 'No group user found with that ID');
    }
}
