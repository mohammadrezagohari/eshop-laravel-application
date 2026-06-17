<?php

namespace App\Services;

use App\Repositories\BasketRepository\IEloquentBasketRepository;

class BasketService
{
    protected $baskets;

    public function __construct(IEloquentBasketRepository $baskets)
    {
        $this->baskets = $baskets;
    }

    public function listForCustomer($user = null, $identity = null)
    {
        return $this->baskets->allItems($user, $identity);
    }

    public function addItem($productId, $count, $user = null, $identity = null)
    {
        return $this->baskets->addItem($productId, $count, $user, $identity);
    }

    public function selectItem($id)
    {
        return $this->baskets->selectItem($id);
    }

    public function updateItem($id, $productId, $count)
    {
        return $this->baskets->updateItem($id, $productId, $count);
    }

    public function deleteItem($id): bool
    {
        return (bool) $this->baskets->deleteItem($id);
    }
}
