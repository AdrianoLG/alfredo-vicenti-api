<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;
    private $request;

    public function __construct(UserService $userService, Request $request)
    {
        $this->userService = $userService;
        $this->request = $request;
    }

    public function createUser()
    {
        if (is_null($this->request->name)) {
            return $this->errorResponse(400, "Field 'name' is missing");
        }
        if (is_null($this->request->email)) {
            return $this->errorResponse(400, "Field 'email' is missing");
        }
        if (is_null($this->request->password)) {
            return $this->errorResponse(400, "Field 'password' is missing");
        }

        $this->userService->postUser($this->request->all());

        return $this->successResponse(201, 'User succesfully created');
    }

    public function getUser(int $id)
    {
        $user = $this->userService->getUser($id);

        if (!is_null($user)) {
            return $this->successResponse(200, null, $user);
        }
        return $this->errorResponse(400, 'No user found with that ID');
    }

    public function updateUserPassword(int $id)
    {
        if (is_null($this->request->password)) {
            return $this->errorResponse(400, "Field 'password' is missing");
        }
        if (is_null($this->request->password_update_token)) {
            return $this->errorResponse(400, "Field 'password_update_token' is missing");
        }

        if ($this->userService->putUserPassword($this->request->password, $this->request->password_update_token, $id)) {
            return $this->successResponse(200, 'Password succesfully updated');
        }
    }

    public function updateUserPasswordToken()
    {
        if (is_null($this->request->email)) {
            return $this->errorResponse(400, "Field 'email' is required");
        }
        $pass_token = $this->userService->putUserPasswordUpdateToken($this->request->email);

        if (!is_null($pass_token)) {
            return $this->successResponse(200, 'Token succesfully inserted', $pass_token);
        }
        return $this->errorResponse(400, 'Email is invalid');
    }

    public function resetUserPasswordToken()
    {
        if (is_null($this->request->email)) {
            return $this->errorResponse(400, "Field 'email' is required");
        }

        if ($this->userService->resetUserPasswordUpdateToken($this->request->email)) {
            return $this->successResponse(200, 'Token succesfully removed');
        }
        return $this->errorResponse(400, 'Email is invalid');
    }

    public function removeUser(int $id)
    {
        if ($this->userService->deleteUser($id)) {
            return $this->successResponse(200, 'User succesfully deleted');
        }
        return $this->errorResponse(400, 'No user found with that ID');
    }
}
