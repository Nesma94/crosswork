<?php
namespace App\Http\Repositories\Interfaces;
use App\Models\Order;
interface OrdersRepositoryInterface
{   
    public function create($data = []);
    public function UserOrders($data = []);
    public function updateOrderTotal($order,$total);
   public function update(Order $order, array $data): Order;
   public function delete(Order $order): bool;
   public function findOrFail($id);

}