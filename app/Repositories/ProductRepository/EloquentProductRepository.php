<?php


namespace App\Repositories\ProductRepository;


use App\Http\Resources\ProductListResource;
use App\Models\Product;

class EloquentProductRepository implements IEloquentProductRepository
{
    /**********************************
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function listActive()
    {
        return ProductListResource::collection(
            Product::show()->orderByDesc('id')
                ->TakeLast()->get()
        );
    }

    /*************************
     * @param $id
     * @return ProductListResource
     */
    public function showActive($id)
    {
        return ProductListResource::make(
            Product::whereId($id)->Show()->firstOrFail()
        );
    }

    public function listForSeller($user)
    {
        $query = Product::orderByDesc('id');

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        return ProductListResource::collection($query->get());
    }

    public function listAll()
    {
        return ProductListResource::collection(
            Product::orderByDesc('id')->get()
        );
    }

}
