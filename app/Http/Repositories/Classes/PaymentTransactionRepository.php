<?php
namespace App\Http\Repositories\Classes;
use App\Http\Repositories\Interfaces\PaymentTransactionRepositoryInterface;
use App\Models\PaymentsTransaction;

class PaymentTransactionRepository implements PaymentTransactionRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 15)
    {
        $query = PaymentsTransaction::query()->whereHas('order', function ($q) {
            $q->where('user_id', auth()->id());
        });

        if (!empty($filters['order_id'])) {
            $query->where('order_id', $filters['order_id']);
        }

        return $query->paginate($perPage);
    }
      public function createPayment(array $data)
    {
        return PaymentsTransaction::create($data);
    }
      public function findById(int $id)
    {
        return PaymentsTransaction::with('order')->find($id);
    }
}
