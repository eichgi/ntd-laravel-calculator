<?php

namespace App\Http\Controllers;

use App\Http\Requests\OperationRequest;
use App\Http\Resources\RecordCollection;
use App\Http\Services\OperationService;
use App\Http\Services\UserService;
use App\Models\Operation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OperationController extends Controller
{

    public function __construct(
        protected UserService      $userService,
        protected OperationService $operationService
    )
    {
    }

    public function calculate(OperationRequest $request): JsonResponse
    {
        try {
            // Authenticated user
            $user = $request->user();

            // Get operation cost
            $operation = Operation::where('type', $request->operationType)->first();

            // If no credit error returned
            if ($user->balance < $operation->cost) {
                return response()->json(['message' => 'Insufficient credit.', 'result' => null], 422);
            }

            $newBalance = $user->balance - $operation->cost;

            // Arithmetic calculation
            $result = $this->operationService->calculate($request->only(['firstValue', 'secondValue', 'operationType']));

            // Operation store
            $this->operationService->storeOperation($request, $operation, $result, $newBalance);

            // Update user's balance
            $this->userService->update(['balance' => $newBalance], $user->id);

            return response()->json(['result' => $result]);
        } catch (\Exception $exception) {
            app('log')->info($exception->getMessage());
            return response()->json(['result' => null, 'message' => $exception->getMessage()], 400);
        }
    }

    public function findRecords(Request $request): JsonResponse
    {
        $params = [
            'user_id' => $request->user()->id,
            'limit' => 10,
            'page' => $request->query('page'),
            'query' => $request->query('query'),
            'order_by' => $request->query('order')
        ];

        $records = $this->operationService->findRecords($params);

        return response()->json($records);
    }

    public function deleteRecord(Request $request, $id): JsonResponse
    {
        $this->operationService->deleteRecord($id);

        return response()->json(['message' => 'Record deleted']);
    }
}
