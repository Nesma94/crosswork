<?php
namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\OrdersService;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Services\OrderItemsService;
use App\Http\Requests\UpdateOrderRequest;

class OrderController extends Controller
{
    private OrdersService $ordersService;
    private OrderItemsService $orderitemsService;

    public function __construct(OrdersService $ordersService, OrderItemsService $orderitemsService)
    {
        $this->ordersService = $ordersService;
        $this->orderitemsService = $orderitemsService;
    }
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $orders = $this->ordersService->Orders($request->all());
        return response()->json($orders->paginate($perPage));
    }

    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        $order = $this->ordersService->create($data);
        $total = $this->orderitemsService->create($order, $data['items']);
        $this->ordersService->updateOrderTotal($order, $total);
        return response()->json($order->load('orderItems', 'paymentTransaction'), 201);
    }

    public function show(Order $order)
    {
        $this->authorizeUser($order);
        return response()->json($order->load('orderItems', 'paymentTransaction'));
    }

    // PUT/PATCH /api/v1/orders/{order}
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $this->authorizeUser($order);

        $data = $request->validated();

        $updatedOrder = $this->ordersService->updateOrder($order, $data);

        return response()->json($updatedOrder);
    }

    public function destroy(Order $order)
    {
        $this->authorizeUser($order);

        $result = $this->ordersService->deleteOrder($order);

        $status = $result['success'] ? 200 : 400;

        return response()->json(['message' => $result['message']], $status);
    }
    protected function authorizeUser(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    }
}
