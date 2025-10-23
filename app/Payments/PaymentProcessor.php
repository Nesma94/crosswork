<?php
namespace App\Payments;

use Exception;
use Throwable;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Str;
use App\Models\PaymentsTransaction;
use App\Payments\PaymentGateWayManager;
use App\Http\Repositories\Interfaces\PaymentTransactionRepositoryInterface;

class PaymentProcessor
{
    protected PaymentGateWayManager $manager;
    private PaymentTransactionRepositoryInterface $repo;

    public function __construct(PaymentGateWayManager $manager, PaymentTransactionRepositoryInterface $repo)
    {
        $this->manager = $manager;
        $this->repo = $repo;
    }

    /**
     * Process payment for an order.
     * Throws exception if order not confirmed or gateway not found.
     */
    public function process(Order $order, array $data = []): PaymentsTransaction
    {
        if ($order->status !== 'confirmed') {
            throw new Exception('Payments can only be processed for confirmed orders.');
        }

       // prevent double successful payments
        if ($order->paymentTransaction()->where('status', 'successful')->exists()) {
            throw new Exception('Order already has a successful payment.');
        }

        $gateway = $this->manager->getGateway($data['payment_method']);
        $result = $gateway->process($order, $data);
        if ((float) $data['amount'] !== (float) $order->total) {
            throw new Exception("Payment amount ({$data['amount']}) does not match order total ({$order->total}).");
        }

        $payment = $this->repo->createPayment([
            'payment_id' => $result['payment_id'] ?? (string) Str::uuid(),
            'order_id' => $order->id,
            'status' => $result['status'] ?? 'pending',
            'payment_method_id' => $data['method'],
        ]);

        return $payment;
    }
}
