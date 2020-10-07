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
        $this->groupService->postGroup($this->request->all());

        return $this->successResponse(201, 'Group succesfully created');
    }

    public function createGroupUser()
    {
        $this->groupService->postGroupUser($this->request->user_id, $this->request->group_id, $this->request->color);

        return $this->successResponse(201, 'User for group succesfully created');
    }

    public function getGroups()
    {
        $groups = $this->groupService->getGroups($this->request->user_id);

        return $this->successResponse(200, null, $groups);
    }

    public function getGroup($id)
    {
        $group = $this->groupService->getGroup($this->request->user_id, $id);

        return $this->successResponse(200, null, $group);
    }
}
