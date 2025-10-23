<?php
namespace App\Http\Repositories\Classes;
use App\Models\PaymentMethod;
use App\Http\Repositories\Interfaces\PaymentMethodRepositoryInterface;

class PaymentMethodRepository implements PaymentMethodRepositoryInterface
{
    public function methodById($id)
    {
        return PaymentMethod::where('id', $id)->first()->name;
    }
}
