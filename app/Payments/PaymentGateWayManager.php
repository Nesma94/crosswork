<?php

namespace App\Payments;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use App\Payments\Interface\PaymentGatewayInterface;
class PaymentGatewayManager
{
    protected Collection $gateways;

    public function __construct(array $gateways = [])
    {
        // Collect gateway service instances
        $this->gateways = collect($gateways);
    }

    /**
     * Retrieve a gateway instance by its method name
     *
     * @param string $method
     * @return PaymentGateWayInterface
     *
     * @throws InvalidArgumentException
     */
    public function getGateway(string $method): PaymentGateWayInterface
    {
        $found = $this->gateways->first(
            fn($gateway) => $gateway->methodName() === $method
        );

        if (!$found) {
            throw new InvalidArgumentException("Payment gateway for method '{$method}' not found.");
        }

        return $found;
    }

    /**
     * Return all registered gateways
     */
    public function all(): Collection
    {
        return $this->gateways;
    }
}