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
        $response = $this->successResponse(201, 'User succesfully created');

        $this->userService->postUser($this->request->all());

        return $response;
    }
}
