<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRoleUpdateRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return UserResource::collection($this->userService->listActive());
    }

    public function show($id)
    {
        return UserResource::make($this->userService->show($id));
    }

    public function updateRole($id, UserRoleUpdateRequest $request)
    {
        return UserResource::make(
            $this->userService->updateRole($id, $request->role)
        );
    }
}
