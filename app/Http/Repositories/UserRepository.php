<?php

namespace App\Http\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{

    public function update(array $data, $id)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }
}
