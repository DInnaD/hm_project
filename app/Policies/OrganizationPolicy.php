<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class OrganizationPolicy
{
    use HandlesAuthorization;
    // private $ability = [];

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
        if ($ability == 'create' && $user->role == 'employer') {
            return true;
        } elseif ($ability == 'create' && $user->role != 'employer') {
            return false;
        };
        if ($user->role == 'admin') {
            return true;
        }
    }

    public function index(User $user)
    {
        return $user->role !== 'worker';
    }

    public function show(User $user)
    {
        return $user->role !== 'worker';
    }

    public function showParams(User $user, Organization $organization)
    {
        return $user->id === $organization->user_id;
    }

    public function update(User $user, Organization $organization)
    {
        return $user->id === $organization->user_id;
    }

    public function delete(User $user, Organization $organization)
    {
        return $user->id === $organization->user_id;
    }

    public function create(User $user)
    {
        return $user->role === 'employer';
    }
}
