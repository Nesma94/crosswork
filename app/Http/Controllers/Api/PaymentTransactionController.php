<?php
namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Payments\PaymentProcessor;
use App\Http\Controllers\Controller;
use App\Http\Services\OrdersService;
use App\Http\Services\PaymentMethodService;
use App\Http\Requests\PaymentTransactionRequest;
use App\Http\Services\PaymentTransactionService;

class PaymentTransactionController extends Controller
{
    protected PaymentProcessor $processor;
    private PaymentTransactionService $paymentService;
    private OrdersService $ordersService;
    private PaymentMethodService $paymentMethodService;


    public function __construct(PaymentProcessor $processor, PaymentTransactionService $paymentService, OrdersService $ordersService, PaymentMethodService $paymentMethodService)
    {
        $this->processor = $processor;
        $this->paymentService = $paymentService;
        $this->ordersService = $ordersService;
        $this->paymentMethodService = $paymentMethodService;

    }

    public function index(Request $request)
    {
        $filters = [
            'order_id' => $request->query('order_id'),
        ];

        $perPage = (int) $request->query('per_page', 15);

        $transactions = $this->paymentService->listTransactions($filters, $perPage);

        return response()->json($transactions);
    }

    public function process(PaymentTransactionRequest $request)
    {
        $data = $request->validated();
        $order = $this->ordersService->OrderById($data['order_id']);

        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $data['payment_method'] = $this->paymentMethodService->methodById($data['method']);
            $payment = $this->processor->process($order, $data);
            return response()->json($payment->fresh(), 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function show($payment)
    {
        try {
            $payment = $this->paymentService->show($payment);
            return response()->json($payment);
        } catch (Exception $e) {
            $status = match ($e->getMessage()) {
                'Unauthorized' => 403,
                'Payment not found' => 404,
                default => 400,
            };

            return response()->json(['message' => $e->getMessage()], $status);
        }
    }
}