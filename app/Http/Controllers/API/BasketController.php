<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BasketStoreRequest;
use App\Http\Resources\BasketResource;
use App\Services\BasketService;
use Illuminate\Support\Facades\Auth;

class BasketController extends Controller
{
    protected $basketService;

    public function __construct(BasketService $basketService)
    {
        $this->basketService = $basketService;
    }

    public function CustomerIndex()
    {
        $result = $this->basketService->listForCustomer(Auth::user(), $this->customerIdentity());

        if ($result === null || $result->isEmpty()) {
            return response()->json(['message' => 'your basket is empty']);
        }

        return BasketResource::collection($result);
    }

    public function CustomerStore(BasketStoreRequest $request)
    {
        $result = $this->basketService
            ->addItem($request->product, $request->count, Auth::user(), $this->customerIdentity());

        if ($result === null) {
            return response()->json(['message' => 'your basket is empty']);
        }

        return BasketResource::make($result)->response()->setStatusCode(200);
    }

    public function show($id)
    {
        $result = $this->basketService->selectItem($id);
        $this->authorize('viewAny', $result);

        return BasketResource::make($result);
    }

    public function updateItem($id, BasketStoreRequest $request)
    {
        $result = $this->basketService->updateItem($id, $request->product, $request->count);

        if ($result) {
            return BasketResource::make($result);
        }

        return response()->json(['message' => 'fails']);
    }

    public function deleteItem($id)
    {
        if ($this->basketService->deleteItem($id)) {
            return response()->json(['message' => 'success']);
        }

        return response()->json(['message' => 'fails']);
    }

    private function customerIdentity()
    {
        $identity = request()->cookie('identity') ?: ($_COOKIE['identity'] ?? null);

        if ($identity) {
            return $identity;
        }

        parse_str(str_replace('; ', '&', request()->server('HTTP_COOKIE', '')), $cookies);

        return $cookies['identity'] ?? null;
    }
}
