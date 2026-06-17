<?php

namespace App\Repositories\BasketRepository;

use App\Models\Basket;
use App\Models\User;
use Illuminate\Support\Str;

class EloquentBasketRepository implements IEloquentBasketRepository
{
    public function index()
    {
        return Basket::query();
    }

    public function addItem($productId, $count, User $user = null, $identity = null)
    {
        if (!$this->checkPolicy($user, $identity)) {
            $identity = (string) Str::uuid();
        }

        $basket = new Basket();
        $basket->cookie_identity = $identity;
        $basket->user_id = $user ? $user->id : null;
        $basket->save();

        $basket->Products()->syncWithPivotValues([$productId], ['count' => $count]);
        $basket->load('Products.User');

        $this->setCookie($identity);

        return $basket;
    }

    public function allItems(User $user = null, $identity = null)
    {
        if ($user) {
            return Basket::with('Products.User')
                ->whereUserId($user->id)
                ->get();
        }

        if (!$identity) {
            return collect();
        }

        return Basket::with('Products.User')
            ->whereCookieIdentity($identity)
            ->get();
    }

    public function selectItem($id)
    {
        return Basket::with('Products.User')->findOrFail($id);
    }

    public function deleteItem($id)
    {
        return Basket::findOrFail($id)->delete();
    }

    public function updateItem($id, $product, $count)
    {
        $basket = Basket::with('Products.User')->findOrFail($id);

        if (!$basket->Products()->where('products.id', $product)->exists()) {
            return null;
        }

        $basket->Products()->updateExistingPivot($product, ['count' => $count]);

        return $basket->fresh('Products.User');
    }

    public function checkPolicy(User $user = null, $identityCookie = null)
    {
        if ($user && Basket::whereUserId($user->id)->exists()) {
            return true;
        }

        if ($identityCookie && Basket::whereCookieIdentity($identityCookie)->exists()) {
            return true;
        }

        return false;
    }

    public function setCookie($identity)
    {
        setcookie('identity', $identity, time() + (86400 * 30), '/');
    }
}
