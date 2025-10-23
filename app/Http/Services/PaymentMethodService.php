<?php 
namespace App\Http\Services;
use App\Http\Repositories\Interfaces\PaymentMethodRepositoryInterface;

class PaymentMethodService{
    private PaymentMethodRepositoryInterface $repo;
    public function __construct(PaymentMethodRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function methodById($id){
        return $this->repo->methodById($id);

    }
}
