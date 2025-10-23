<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentTransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Prefix: /api
| Middleware: api
|--------------------------------------------------------------------------
*/
 Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    //  Protected routes (require valid JWT)
    Route::middleware('auth:api')->group(function () {
        // Authenticated user info
        Route::post('/logout', [AuthController::class, 'logout']);

        //  ORDER MANAGEMENT
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']); // list all orders (with filters)
            Route::get('/{order}', [OrderController::class, 'show']); // view single order
            Route::post('/', [OrderController::class, 'store']); // create order
            Route::put('/{order}', [OrderController::class, 'update']); // update order
            Route::delete('/{order}', [OrderController::class, 'destroy']); // delete order
        });

        //  PAYMENT MANAGEMENT
        Route::prefix('payments')->group(function () {
            Route::get('/', [PaymentTransactionController::class, 'index']); // list all payments
            Route::get('/{payment}', [PaymentTransactionController::class, 'show']); // show single payment
            Route::post('/process', [PaymentTransactionController::class, 'process']); // process payment for an order
        });
    });


