<?php

namespace App\Http\Services;

use App\Http\enums\operations;
use App\Http\Repositories\OperationRepositoryInterface;
use DivisionByZeroError;
use Exception;
use Illuminate\Support\Facades\Http;

class OperationService
{
    public function __construct(
        protected OperationRepositoryInterface $operationRepository
    )
    {
    }

    public function calculate($payload): int|float|string
    {
        try {
            return match ($payload['operationType']) {
                operations::ADDITION->name => $payload['firstValue'] + $payload['secondValue'],
                operations::SUBTRACTION->name => $payload['firstValue'] - $payload['secondValue'],
                operations::MULTIPLICATION->name => $payload['firstValue'] * $payload['secondValue'],
                operations::DIVISION->name => $payload['firstValue'] / $payload['secondValue'],
                operations::SQUARE_ROOT->name => sqrt($payload['firstValue']),
                operations::RANDOM_STRING->name => $this->getRandomString(),
                default => 0,
            };
        } catch (Exception | DivisionByZeroError $exception) {
            return 0;
        }
    }

    public function storeOperation($request, $operation, $result, $newBalance): void
    {
        $request->user()->records()->create([
            'amount' => 1,
            'user_balance' => $newBalance,
            'operation_id' => $operation->id,
            'operation_response' => $result
        ]);
    }

    public function getRandomString(): string
    {
        return Http::get('https://www.random.org/strings/?num=1&format=plain&len=10&loweralpha=on');
    }

    public function findRecords($params): array
    {
        $records = $this->operationRepository->find($params);

        return [
            'records' => $records->map(function ($record) {
                return [
                    'id' => $record->id,
                    'type' => $record->operation->type,
                    'result' => $record->operation_response,
                    'date' => $record->created_at->format('d/m/y H:i')
                ];
            }),
            'current_page' => $records->currentPage(),
            'next_page_url' => $records->nextPageUrl(),
            'prev_page_url' => $records->previousPageUrl(),
            'total' => $records->total()
        ];
    }

    public function deleteRecord($id)
    {
        $this->operationRepository->delete($id);
    }
}
