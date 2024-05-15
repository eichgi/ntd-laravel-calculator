<?php

namespace App\Http\Services;

use App\Http\Repositories\UserRepositoryInterface;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    )
    {
    }

    public function update(array $data, $id)
    {
        return $this->userRepository->update($data, $id);
    }
}
