<?php
namespace App\Payments\Interface;

use App\Models\Order;

interface PaymentGatewayInterface
{
public function process(Order $order, array $data): array;
public function methodName(): string;
}