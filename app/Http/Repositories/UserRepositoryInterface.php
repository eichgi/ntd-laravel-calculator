<?php

namespace App\Http\Repositories;

interface UserRepositoryInterface
{
    public function update(array $data, $id);

    public function find($id);
}
