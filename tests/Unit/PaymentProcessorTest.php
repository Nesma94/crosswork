<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Mockery;
use App\Models\Order;
use App\Models\PaymentsTransaction;
use App\Payments\PaymentProcessor;
use App\Payments\PaymentGateWayManager;
use App\Http\Repositories\Interfaces\PaymentTransactionRepositoryInterface;
use App\Payments\Interface\PaymentGatewayInterface;
use Exception;

class PaymentProcessorTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_processes_payment_successfully()
    {
        // Arrange
        $order = Mockery::mock(Order::class)->makePartial();
        $order->id = 1;
        $order->total = 100;
        $order->status = 'confirmed';
        $order->shouldReceive('paymentTransaction->where->exists')->andReturn(false);

        $data = [
            'payment_method' => 'visa',
            'amount' => 100,
            'method' => 1,
        ];

        $gatewayMock = Mockery::mock(PaymentGatewayInterface::class);
        $gatewayMock->shouldReceive('process')->once()->andReturn([
            'payment_id' => 'TX123',
            'status' => 'successful',
        ]);

        $managerMock = Mockery::mock(PaymentGateWayManager::class);
        $managerMock->shouldReceive('getGateway')->with('visa')->andReturn($gatewayMock);

        $repoMock = Mockery::mock(PaymentTransactionRepositoryInterface::class);
        $repoMock->shouldReceive('createPayment')
            ->once()
            ->andReturn(new PaymentsTransaction(['status' => 'successful']));

        // Act
        $processor = new PaymentProcessor($managerMock, $repoMock);
        $result = $processor->process($order, $data);

        // Assert
        $this->assertInstanceOf(PaymentsTransaction::class, $result);
        $this->assertEquals('successful', $result->status);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_throws_exception_if_amount_mismatch()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('does not match order total');

        $order = Mockery::mock(Order::class)->makePartial();
        $order->id = 1;
        $order->total = 100;
        $order->status = 'confirmed';
        $order->shouldReceive('paymentTransaction->where->exists')->andReturn(false);

        $data = [
            'payment_method' => 'visa',
            'amount' => 200,
            'method' => 1,
        ];

        $gatewayMock = Mockery::mock(PaymentGatewayInterface::class);
        $gatewayMock->shouldReceive('process')->andReturn(['status' => 'pending']);

        $managerMock = Mockery::mock(PaymentGateWayManager::class);
        $managerMock->shouldReceive('getGateway')->with('visa')->andReturn($gatewayMock);

        $repoMock = Mockery::mock(PaymentTransactionRepositoryInterface::class);

        $processor = new PaymentProcessor($managerMock, $repoMock);
        $processor->process($order, $data);
    }
}
