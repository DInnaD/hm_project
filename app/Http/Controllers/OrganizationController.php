<?php

namespace App\Http\Controllers;

use App\Http\Requests\Organization\OrganizationStoreRequest;
use App\Http\Requests\Organization\OrganizationUpdateRequest;
use App\Http\Resources\OrganizationCollection;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::with(['user'])->get();

        return new OrganizationCollection($organizations);
    }

    public function store(OrganizationStoreRequest $request)
    {
        $organization = Organization::create($request->all());

        return new OrganizationResource($organization);
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
            ($value->workers_booked < $value->workers_amount) ? $value->status = 'active' : $value->status = 'closed';
        });

        $organization->_vacancies = $_vacancies;

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

        return new OrganizationResource($organization);
    }

    public function update(OrganizationUpdateRequest $request, Organization $organization)
    {
        $organization->update($request->all());

        return new OrganizationResource($organization);
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();

        return new OrganizationResource($organization);
    }
}
