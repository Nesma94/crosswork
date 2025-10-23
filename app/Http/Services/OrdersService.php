<?php
namespace App\Http\Services;

use App\Models\Order;
use App\Http\Repositories\Interfaces\OrdersRepositoryInterface;

class OrdersService
{
    private OrdersRepositoryInterface $repo;
    public function __construct(OrdersRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function Orders($request) {
       return $this->repo->UserOrders($request);

    }
    public function create($data = [])
    {
        return $this->repo->create($data);
    }
    public function updateOrderTotal($order,$total)
    {
        return $this->repo->updateOrderTotal($order,$total);
    }
     public function updateOrder(Order $order, array $data): Order
    {
        return $this->repo->update($order, $data);
    }
    public function deleteOrder(Order $order): array
    {
        if ($order->paymentTransaction()->exists()) {
            return [
                'success' => false,
                'message' => 'Cannot delete order with payments'
            ];
        }

        $deleted = $this->repo->delete($order);

        return [
            'success' => $deleted,
            'message' => $deleted ? 'Order deleted' : 'Failed to delete order'
        ];
    }

    public function OrderById($id){
        return $this->repo->findOrFail($id);
    }
}
