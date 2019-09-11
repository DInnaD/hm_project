<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before($user, $ability)
    {
        if ($user->role === 'admin') {
            return true;
        };
    }

    public function index(User $user)
    {
        return $user->id === 'admin';
    }

    public function show(User $user, User $current_user)
    {
        return $user->id === $current_user->id;
    }

    public function update(User $user, User $current_user)
    {
        return $user->id === $current_user->id;
    }

    public function delete(User $user, User $current_user)
    {
        return $user->id === $current_user->id;
    }
}
