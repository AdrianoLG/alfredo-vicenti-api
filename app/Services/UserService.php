<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function getUser()
    {
    }

    public function postUser(array $user)
    {
        $user['password'] = Hash::make($user['password']);
        $this->model->create($user);
    }

    public function putUser(array $user, int $id)
    {
    }

    public function deleteUser(array $user, int $id)
    {
    }
}
