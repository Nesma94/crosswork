<?php
namespace App\Payments;

use Throwable;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Str;
use App\Models\PaymentsTransaction;

class PaymentTransactions
{
    protected PaymentGatewayManager $manager;

    public function __construct(PaymentGatewayManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Process payment for an order.
     * Throws exception if order not confirmed or gateway not found.
     */
    public function process(Order $order, string $method, array $data = []): PaymentsTransaction
    {
        if ($order->status !== 'confirmed') {
            throw new \Exception('Payments can only be processed for confirmed orders.');
        }

        // Optional: prevent double successful payments
        if ($order->paymentTransaction()->where('status', 'successful')->exists()) {
            throw new \Exception('Order already has a successful payment.');
        }

        $gateway = $this->manager->getGateway($method);
        $result = $gateway->process($order, $data);

        // persist payment
        $payment = PaymentsTransaction::create([
            'payment_id' => $result['payment_id'] ?? (string) Str::uuid(),
            'order_id' => $order->id,
            'status' => $result['status'] ?? 'pending',
            // 'method' => $method,
            // 'meta' => $result['meta'] ?? [],
        ]);

        return $payment;
    }
}
