<?php

namespace App\Http\Services;

use Exception;
use App\Http\Repositories\Interfaces\PaymentTransactionRepositoryInterface;

class PaymentTransactionService
{
    private PaymentTransactionRepositoryInterface $repo;

    public function __construct(PaymentTransactionRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function listTransactions(array $filters = [], int $perPage = 15)
    {
        return $this->repo->getAll($filters, $perPage);
    }
     public function show($id)
    {
        $payment = $this->repo->findById($id);

        if (!$payment) {
            throw new Exception('Payment not found');
        }

        if ($payment->order->user_id !== auth()->id()) {
            throw new Exception('Unauthorized');
        }

        return $payment;
    }
}