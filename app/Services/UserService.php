<?php

namespace App\Services;

use App\Repositories\UserRepository\IEloquentUserRepository;

class UserService
{
    protected $users;

    public function __construct(IEloquentUserRepository $users)
    {
        $this->users = $users;
    }

    public function listActive()
    {
        return $this->users->listActive();
    }

    public function show($id)
    {
        return $this->users->show($id);
    }

    public function updateRole($id, string $role)
    {
        return $this->users->updateRole($id, $role);
    }
}
