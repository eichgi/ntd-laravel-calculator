<?php

namespace App\Http\Repositories;

use App\Models\Record;

class OperationRepository implements OperationRepositoryInterface
{
    public function find(array $params)
    {
        $columns = [
            'date' => 'created_at',
            'result' => 'operation_response'
        ];

        $query = Record::where('user_id', $params['user_id'])->with(['operation']);

        if ($params['query']) {
            $query->where('operation_response', 'like', "%{$params['query']}%");
        }

        if ($params['order_by']) {
            $query->orderBy($columns[$params['order_by']], 'ASC');
        } else {
            $query->orderBy('created_at', 'DESC');
        }

        return $query->paginate($params['limit'], ['*'], 'page', $params['page']);
    }

    public function delete(int $id): int
    {
        return Record::destroy($id);
    }
}
