<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::with(['creator'])->get();
        return response()->json([
            'success' => true,
            'data' => $organizations
        ], 200);
    }

    public function store(Request $request)
    {
        $organization = Organization::create($request->all());
        return response()->json([
            'success' => true,
            'data' => $organization
        ], 201);
    }

    public function show(Request $request, Organization $organization)
    {
        // get requests
        $_vacancies = $request->get('vacancies');
        $_workers = $request->get('workers');

        // get organization with relationships and count workers in vacancy
        $organization = $organization->load(['creator', 'vacancies' => function ($query) {
            $query->withCount(['workers AS workers_booked'])->get();
        }]);
        $organization->vacancies->each(function ($value) {
            if ($value->workers_booked < $value->workers_amount) {
                $value->status = 'active';
            }
        });
        // validation requests
        if (isset($_vacancies) and $_vacancies != 0) {
            $vacancies = $organization->vacancies;
            foreach ($vacancies as $key => $vacancy) {
                if ($_vacancies == 1) {
                    if ($vacancy->workers_amount <= $vacancy->workers_booked) {
                        unset($vacancies[$key]);
                    }
                } elseif ($_vacancies == 2) {
                    if ($vacancy->workers_amount > $vacancy->workers_booked) {
                        unset($vacancies[$key]);
                    }
                } elseif ($_vacancies == 3) { }
            }
            if ($_workers == 1) {
                // add workers collection 
                $workers = [];
                foreach ($organization->vacancies as $vacancy) {
                    array_push($workers, $vacancy->workers);
                    unset($vacancy['workers']);
                }
                $organization->workers = collect($workers)->collapse()->all();
            }
        } else {
            unset($organization['vacancies']);
        }

        return response()->json([
            'success' => true,
            'data' => $organization
        ], 200);
    }

    public function update(Request $request, Organization $organization)
    {
        $organization->update($request->all());
        return response()->json([
            'success' => true,
            'data' => $organization
        ], 202);
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();
        return response()->json([
            'success' => true,
        ], 204);
    }
}
