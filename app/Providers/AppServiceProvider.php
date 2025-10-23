<?php

namespace App\Providers;

use App\Payments\PaymentProcessor;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use App\Payments\Services\CashGateWay;
use App\Payments\PaymentGateWayManager;
use Illuminate\Support\ServiceProvider;
use App\Payments\Services\CreditCardGateWay;
use App\Http\Repositories\Classes\UserRepository;
use App\Http\Repositories\Classes\OrderRepository;
use App\Http\Repositories\Classes\OrdersRepository;
use App\Http\Repositories\Classes\OrderItemsRepository;
use App\Http\Repositories\Classes\PaymentMethodRepository;
use App\Http\Repositories\Interfaces\UserRepositoryInterface;
use App\Http\Repositories\Classes\PaymentTransactionRepository;
use App\Http\Repositories\Interfaces\OrdersRepositoryInterface;
use App\Http\Repositories\Interfaces\OrderItemsRepositoryInterface;
use App\Http\Repositories\Interfaces\PaymentMethodRepositoryInterface;
use App\Http\Repositories\Interfaces\PaymentTransactionRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
            $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
            $this->app->bind(OrdersRepositoryInterface::class, OrdersRepository::class);
            $this->app->bind(OrderItemsRepositoryInterface::class, OrderItemsRepository::class);
            $this->app->bind(PaymentTransactionRepositoryInterface::class, PaymentTransactionRepository::class);
            $this->app->bind(PaymentMethodRepositoryInterface::class, PaymentMethodRepository::class);

        $this->app->register(\Tymon\JWTAuth\Providers\LaravelServiceProvider::class);

        $this->app->singleton(CreditCardGateWay::class);
        $this->app->singleton(CashGateWay::class);

        $this->app->singleton(PaymentGateWayManager::class, function ($app) {
            return new PaymentGateWayManager([$app->make(CreditCardGateWay::class), $app->make(CashGateWay::class)]);
        });
        $this->app->singleton(PaymentProcessor::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
