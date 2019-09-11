<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\HandlesAuthorization;

class VacancyPolicy
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
        return $user->role === 'admin';
    }

    public function create()
    {
        return false;
    }

    public function update(User $user, Vacancy $vacancy)
    {
        return $user->id === $vacancy->organization->user_id;
    }

    public function delete(User $user, Vacancy $vacancy)
    {
        return $user->id === $vacancy->organization->user_id;
    }

    public function book()
    {
        return false;
    }
}
