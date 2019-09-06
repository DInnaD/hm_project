<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StatsController extends Controller
{
    public function users()
    {
        $users = User::all();

        $worker = $users
            ->filter(function ($value) {
                return $value->role == 'worker';
            })
            ->count();

        $employer = $users
            ->filter(function ($value) {
                return $value->role == 'employer';
            })
            ->count();

        $admin = $users->count() - $worker - $employer;

        $user = collect(['worker' => $worker, 'employer' => $employer, 'admin' => $admin]);

        return response()->json([
            'success' => true,
            'data' => $user,
        ], 200);
    }

    public function vacancies()
    {
        $vacancies = Vacancy::withCount(['workers AS workers_booked'])->get();

        $all = $vacancies->count();
        $closed = $vacancies
            ->filter(function ($value) {
                return $value->workers_amount <= count($value->workers);
            })
            ->count();

        $active = $all - $closed;

        $vacancy = collect(['active' => $active, 'closed' => $closed, 'all' => $all]);

        return response()->json([
            'success' => true,
            'data' => $vacancy,
        ], 200);
    }

    public function organization()
    {
        $organizations = Organization::all();

        $all = $organizations->count();

        $active = count($organizations->where('deleted_at', '=', null)->all());
        $softDelete = $all - $active;
        $organization = collect(['active' => $active, 'softDeleted' => $softDelete, 'all' => $all]);

        return response()->json([
            'success' => true,
            'data' => $organization,
        ], 200);
    }
}
