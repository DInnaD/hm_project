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
        $only_active = request()->get('only_active');
        return $only_active === 'false';
    }

    public function create(User $user)
    {
        $organization_id = request()->post('organization_id');
        $organization = Organization::where('id', $organization_id)->first();

        return $organization->user_id === $user->id;
    }

    public function update(User $user, Vacancy $vacancy)
    {
        return $user->id === $vacancy->organization->user_id;
    }

    public function delete(User $user, Vacancy $vacancy)
    {
        return $user->id === $vacancy->organization->user_id;
    }

    public function book(User $user)
    {
        $user_id = request()->post('user_id');

        return $user_id === $user->id;
    }
}
