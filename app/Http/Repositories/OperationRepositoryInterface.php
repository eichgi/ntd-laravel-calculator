<?php

namespace App\Http\Repositories;

interface OperationRepositoryInterface
{
    public function find(array $params);

    public function delete(int $id);
}
