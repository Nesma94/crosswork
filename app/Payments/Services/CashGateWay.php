<?php
namespace App\Payments\Services;

use App\Models\Order;
use App\Payments\Interface\PaymentGatewayInterface;

class CashGateway implements PaymentGatewayInterface
{
    public function methodName(): string
    {
        return 'Cash';
    }

    public function process(Order $order, array $data): array
    {
        $paymentId = 'pp_' . uniqid();
        $status = $order->total <= 1000 ? 'successful' : (rand(0, 1) ? 'successful' : 'failed');

        return [
            'status' => $status,
            'payment_id' => $paymentId,
            'extra_details' => [

            ],
        ];
    }
}
