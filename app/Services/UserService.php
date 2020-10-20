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

    public function getUser(int $id)
    {
        $user = User::where('id', $id)->first(['name', 'email', 'shared_comments', 'shared_ratings']);
        return $user;
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
                    'grant_type' => $grant_type,
                    'client_id' => $client_id,
                    'client_secret' => $client_secret,
                    'email' => $user->email,
                    'password' => $password
                ])->getData();
        }
        return null;
    }

    public function putUserPassword(string $password, string $password_update_token, int $id)
    {
        $pass = Hash::make($password);
        $user = User::find($id);
        $pass_update_token = $user->password_update_token;

        if (!is_null($pass_update_token) && $pass_update_token == $password_update_token) {
            $user->password = $pass;
            $user->save();
            return true;
        }
        return false;
    }

    public function putUserPasswordUpdateToken(string $email)
    {
        $user = User::where('email', $email)->first();

        if (!is_null($user->id)) {
            $user->password_update_token = Hash::make(Str::random());
            $user->save();
            return $user->password_update_token;
        }
        return null;
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

    public function deleteUser(int $id)
    {
        $user = User::find($id);

        if (!is_null($user)) {
            $user->delete();
            return true;
        }
        return false;
    }
}
