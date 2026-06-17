<?php

namespace App\Repositories\BasketRepository;

use App\Models\User;

interface IEloquentBasketRepository
{
    public function index();

    public function addItem($productId, $count, User $user = null, $identity = null);

    public function allItems(User $user = null, $identity = null);

    public function selectItem($id);

    public function deleteItem($id);

    public function updateItem($id, $product, $count);

    public function checkPolicy(User $user = null, $identityCookie = null);

    public function setCookie($identity);
}
