<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{    use HasFactory;


    //
      protected $fillable = [
        'user_id',
        'total',
        'status',
    ];
    /**
     * Get all of the orderItems for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    /**
     * Get all of the paymentTransaction for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentTransaction(): HasMany
    {
        return $this->hasMany(PaymentsTransaction::class);
    }
}
