<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        return $this->productService->listActive();
    }

    public function show($id)
    {
        return $this->productService->showActive($id);
    }

    public function sellerIndex()
    {
        return $this->productService->listForSeller(request()->user());
    }

    public function adminIndex()
    {
        return $this->productService->listAll();
    }
}
