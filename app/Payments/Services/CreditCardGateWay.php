<?php
namespace App\Payments\Services;

use App\Models\Order;
use App\Payments\Interface\PaymentGatewayInterface;

class CreditCardGateway implements PaymentGatewayInterface
{
    public function methodName(): string
    {
        return 'credit_card';
    }

    public function process(Order $order, array $data): array
    {
        // Simulation: succeed if total <= 1000, else randomize
        $paymentId = 'cc_' . uniqid();
        $status = $order->total <= 1000 ? 'successful' : (rand(0, 1) ? 'successful' : 'failed');

        return [
            'status' => $status,
            'payment_id' => $paymentId,
            'extra_details' => [
                'simulated' => true,
                'card_last4' => $data['card_last4'] ?? null,
            ],
        ];
    }
}
