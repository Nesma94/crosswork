<?php
namespace App\Http\Services;

use App\Http\Repositories\Interfaces\OrdersRepositoryInterface;
use App\Http\Repositories\Interfaces\OrderItemsRepositoryInterface;

class OrderItemsService
{
    private OrderItemsRepositoryInterface $repo;
    public function __construct(OrderItemsRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

 
    public function create($order,$items = [])
    {
        return $this->repo->createForOrder($order,$items);
    }
}
