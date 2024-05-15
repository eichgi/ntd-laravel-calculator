<?php

namespace Tests\Unit;

use App\Http\enums\operations;
use App\Http\Repositories\OperationRepository;
use App\Http\Repositories\OperationRepositoryInterface;
use App\Http\Services\OperationService;
use Tests\TestCase;

class CalculatorTest extends TestCase
{
    private OperationService $operationService;

    protected function setUp(): void
    {
        parent::setUp();
        app()->bind(
            OperationRepositoryInterface::class,
            OperationRepository::class
        );
        $this->operationService = new OperationService(
            app()->make(OperationRepositoryInterface::class)
        );
    }

    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    public function test_random_string_gets_string(): void
    {
        $response = $this->operationService->getRandomString();
        $this->assertIsString($response);
        //fwrite(STDERR, print_r($response, TRUE));
    }

    public function test_calculate_addition_is_correct()
    {
        $payload = [
            'operationType' => operations::ADDITION->name,
            'firstValue' => 1,
            'secondValue' => 2,
        ];

        $response = $this->operationService->calculate($payload);
        $this->assertEquals($payload['firstValue'] + $payload['secondValue'], $response);
    }

    public function test_division_by_zero_or_any_other_unsupported_operation_returns_zero()
    {
        $payload = [
            'operationType' => operations::DIVISION->name,
            'firstValue' => 1,
            'secondValue' => 0,
        ];

        $response = $this->operationService->calculate($payload);
        $this->assertEquals(0, $response);
    }
}
