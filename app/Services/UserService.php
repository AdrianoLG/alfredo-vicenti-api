<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    private $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function getUser(int $user_id)
    {
        $user = User::where('id', $user_id)->first();
        $groups = $user->groups;
        $userGroups = [];
        foreach ($groups as $group) {
            array_push($userGroups, ['name' => $group->name, 'users' => $group->users]);
        }
        $user->userGroups;
        return $user;
    }

    public function userExists(string $user_email)
    {
        $user = User::where('email', $user_email)->first();
        if (!is_null($user)) {
            return true;
        }
        return false;
    }

    public function postUser(array $user)
    {
        $user['password'] = Hash::make($user['password']);
        $this->model->create($user);
    }

    public function postUserLogin($grant_type, $client_id, $client_secret, $email, $password)
    {
        $user = User::where('email', $email)->first();

        if (!is_null($user) && Hash::check($password, $user->password)) {
            return response()->json([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $password,
                    'grant_type' => $grant_type,
                    'client_id' => $client_id,
                    'client_secret' => $client_secret
                ])->getData();
        }
        return null;
    }

    public function putUserData(int $user_id, string $name, string $email)
    {
        $user = User::find($user_id);

        if (!is_null($name) && !is_null($email)) {
            $user->name = $name;
            $user->email = $email;
            $user->save();
            return true;
        }
        return false;
    }

    public function putUserPassword(string $password, string $password_update_token, int $user_id)
    {
        $pass = Hash::make($password);
        $user = User::find($user_id);
        $pass_update_token = $user->password_update_token;

        if (!is_null($pass_update_token) && $pass_update_token == $password_update_token) {
            $user->password = $pass;
            $user->save();
            return true;
        }
        return false;
    }

    public function putUserPasswordUpdateToken(int $user_id, string $token)
    {
        $user = User::find($user_id);

        if (!is_null($user->id)) {
            $user->password_update_token = Hash::make($token);
            
            $user->save();
            return true;
        }
        return false;
    }

    public function resetUserPasswordUpdateToken(string $email)
    {
        $user = User::where('email', $email)->first();
        if (!is_null($user->id)) {
            $user->password_update_token = null;
            $user->save();
            return true;
        }
        return false;
    }

    public function deleteUser(int $user_id)
    {
        $user = User::find($user_id);

        if (!is_null($user)) {
            $user->delete();
            return true;
        }
        return false;
    }
}
