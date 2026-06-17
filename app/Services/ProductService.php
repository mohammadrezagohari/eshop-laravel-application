<?php

namespace App\Services;

use App\Repositories\ProductRepository\IEloquentProductRepository;

class ProductService
{
    protected $products;

    public function __construct(IEloquentProductRepository $products)
    {
        $this->products = $products;
    }

    public function listActive()
    {
        return $this->products->listActive();
    }

    public function showActive($id)
    {
        return $this->products->showActive($id);
    }
}
