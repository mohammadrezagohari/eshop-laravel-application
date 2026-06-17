<?php

namespace App\Providers;

use App\Repositories\BasketRepository\EloquentBasketRepository;
use App\Repositories\BasketRepository\IEloquentBasketRepository;
use App\Repositories\ProductRepository\EloquentProductRepository;
use App\Repositories\ProductRepository\IEloquentProductRepository;
use App\Repositories\TicketRepository\EloquentTicketRepository;
use App\Repositories\TicketRepository\IEloquentTicketRepository;
use App\Repositories\UserRepository\EloquentUserRepository;
use App\Repositories\UserRepository\IEloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(IEloquentUserRepository::class, EloquentUserRepository::class);
        $this->app->bind(IEloquentProductRepository::class, EloquentProductRepository::class);
        $this->app->bind(IEloquentBasketRepository::class, EloquentBasketRepository::class);
        $this->app->bind(IEloquentTicketRepository::class, EloquentTicketRepository::class);
    }
}
