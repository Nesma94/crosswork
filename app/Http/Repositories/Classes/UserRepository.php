<?php
namespace App\Http\Repositories\Classes;
use App\Models\User;
use App\Http\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function create($data = [])
    {
        return User::create($data);
    }
}
