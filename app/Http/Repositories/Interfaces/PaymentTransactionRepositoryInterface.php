<?php
namespace App\Http\Repositories\Interfaces;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
interface PaymentTransactionRepositoryInterface
{   
   
    public function getAll(array $filters = [], int $perPage = 15);
    public function createPayment(array $data);
    public function findById(int  $id);

}
