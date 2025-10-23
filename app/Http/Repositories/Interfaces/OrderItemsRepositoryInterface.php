<?php
namespace App\Http\Repositories\Interfaces;
use App\Models\Order;
interface OrderItemsRepositoryInterface
{   
    public function createForOrder($order,array $items);
    public function delete($order);
  
}
