<?php

namespace App\Http\Controllers;

use App\Http\Requests\Organization\OrganizationStoreRequest;
use App\Http\Requests\Organization\OrganizationUpdateRequest;
use App\Http\Resources\OrganizationCollection;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function index()
    {
        $this->authorize('index', Organization::class);

        (Auth::guard('api')->user()->role == 'admin') ? $organizations = Organization::with(['user'])->get() : $organizations = Organization::where('user_id', Auth::guard('api')->id())->with(['user'])->get();

        return new OrganizationCollection($organizations);
    }

    public function store(OrganizationStoreRequest $request)
    {
        $this->authorize('create', Organization::class);

        $data = $request->validated();
        $data['user_id'] = Auth::guard('api')->id();

        $organization = Organization::create($data);

        return new OrganizationResource($organization);
    }

    public function show(Request $request, Organization $organization)
    {
        $this->authorize('show', $organization);

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

            $this->authorize('showParams', $organization);

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
        $this->authorize('update', $organization);

        $organization->update($request->validated());

        return new OrganizationResource($organization);
    }

    public function destroy(Organization $organization)
    {
        $this->authorize('delete', $organization);

        $organization->delete();

        return new OrganizationResource($organization);
    }
}
