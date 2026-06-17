<?php


namespace App\Repositories\UserRepository;


use App\Models\User;

class EloquentUserRepository implements IEloquentUserRepository
{
    public function listActive()
    {
        return User::where('active','=',1)->orderByDesc('id')->take(10)->get();
    }
    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function updateRole($id, string $role)
    {
        $user = User::findOrFail($id);
        $user->update(['role' => $role]);

        return $user->refresh();
    }
}
