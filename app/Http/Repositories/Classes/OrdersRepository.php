<?php
namespace App\Http\Repositories\Classes;
use App\Models\Order;
use App\Http\Repositories\Interfaces\UserRepositoryInterface;
use App\Http\Repositories\Interfaces\OrdersRepositoryInterface;

class OrdersRepository implements OrdersRepositoryInterface
{
    public function UserOrders($data = [])
    {
        $query = Order::with('orderItems', 'paymentTransaction')->where('user_id', auth()->id());
        if (isset($data['status'])) {
            $query->where('status', $data['status']);
        }
        return $query;
    }
    public function create($data = [])
    {
        return Order::create([
            'user_id' => auth()->id(),
            'total' => 0,
            'status' => $data['status'] ?? 'pending',
        ]);
    }
    public function updateOrderTotal($order ,$total)
    {
        return $order->update(["total"=>$total]);
    }

        public function update(Order $order, array $data): Order
    {
        if (isset($data['status'])) {
            $order->status = $data['status'];
        }

        if (isset($data['items'])) {
            $order->orderItems()->delete();
            $total = 0;

            foreach ($data['items'] as $item) {
                $lineTotal = $item['quantity'] * $item['unit_price'];

                $order->orderItems()->create([
                    'product_name' => $item['product_name'],
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $item['unit_price'],
                    'line_total'   => $lineTotal,
                ]);

                $total += $lineTotal;
            }

            $order->total = $total;
        }

        $order->save();

        return $order->load('orderItems', 'paymentTransaction');
    }
     public function delete(Order $order): bool
    {
        if ($order->paymentTransaction()->exists()) {
            return false;
        }

        return $order->delete();
    }

    public function findOrFail($id){
       return Order::findOrFail($id);
    }

}
