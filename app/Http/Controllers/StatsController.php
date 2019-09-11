<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatsResource;
use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
// use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate as IlluminateGate;

class StatsController extends Controller
{
    public function users()
    {
        if (!IlluminateGate::allows('stats-admin')) {
            return response()->json([
                'success' => false,
                'exception' => 'This action is unauthorized.'
            ], 200);
        }

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

        return new StatsResource($user);
    }

    public function vacancies()
    {
        if (!IlluminateGate::allows('stats-admin')) {
            return response()->json([
                'success' => false,
                'exception' => 'This action is unauthorized.'
            ], 200);
        }

        $vacancies = Vacancy::withCount(['workers AS workers_booked'])->get();

        $all = $vacancies->count();
        $closed = $vacancies
            ->filter(function ($value) {
                return $value->workers_amount <= count($value->workers);
            })
            ->count();

        $active = $all - $closed;

        $vacancy = collect(['active' => $active, 'closed' => $closed, 'all' => $all]);

        return new StatsResource($vacancy);
    }

    public function organizations()
    {
        if (!IlluminateGate::allows('stats-admin')) {
            return response()->json([
                'success' => false,
                'exception' => 'This action is unauthorized.'
            ], 200);
        }

        $organizations = Organization::all();

        $all = $organizations->count();

        $active = count($organizations->where('deleted_at', '=', null)->all());
        $softDelete = $all - $active;
        $organization = collect(['active' => $active, 'softDeleted' => $softDelete, 'all' => $all]);

        return new StatsResource($organization);
    }
}
