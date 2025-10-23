<?php
namespace App\Http\Repositories\Classes;
use App\Models\OrderItem;
use App\Http\Repositories\Interfaces\OrderItemsRepositoryInterface;

class OrderItemsRepository implements OrderItemsRepositoryInterface
{
   
    public function createForOrder($order, array $items)
    {
        $total = 0;

        foreach ($items as $item) {
            $lineTotal = $item['quantity'] * $item['unit_price'];

            $order->orderItems()->create([
                'product_name' => $item['product_name'],
                'quantity'     => $item['quantity'],
                'unit_price'   => $item['unit_price'],
                'line_total'   => $lineTotal,
            ]);

            $total += $lineTotal;
        }

        return $total;
    }
    public function delete($order){
        
    }
}
