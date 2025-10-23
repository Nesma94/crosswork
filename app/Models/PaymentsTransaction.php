<?php

namespace App\Models;

use Faker\Provider\ar_EG\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentsTransaction extends Model
{
      protected $fillable = [
        'order_id',
        'payment_method_id',
        'status',
    ];
    /**
     * Get the order that owns the PaymentsTransaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
