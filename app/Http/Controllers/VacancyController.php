<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vacancy\VacancyBookRequest;
use App\Http\Requests\Vacancy\VacancyStoreRequest;
use App\Http\Requests\Vacancy\VacancyUpdateRequest;
use App\Http\Resources\VacancyCollection;
use App\Http\Resources\VacancyResource;
use App\Models\Organization;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacancyController extends Controller
{
    public function index(Request $request)
    {
        $only_active = $request->get('only_active');

        $vacancies = Vacancy::withCount(['workers AS workers_booked'])->get();

        $vacancies = $vacancies->filter(function ($value) use ($only_active) {
            if ($only_active != "false") {
                if ($value->workers_booked < $value->workers_amount) return $value;
            } else {
                $this->authorize('index', Vacancy::class);
                return $value;
            }
        });
        return new VacancyCollection($vacancies);
    }

    public function store(VacancyStoreRequest $request)
    {
        $data = $request->all();
        $organization = Organization::where('id', $data['organization_id'])->first();

        if ($organization->user_id != Auth::guard('api')->id()) $this->authorize('create', Vacancy::class);

        $vacancy = Vacancy::create($data);

        return new VacancyResource($vacancy);
    }

    public function show(Vacancy $vacancy)
    {

        $vacancy->load(['workers']);

        $vacancy->workers_booked = count($vacancy->workers()->get());
        if (Auth::guard('api')->user()->role !== 'worker') {
            // $this->authorize('show', $vacancy);
            $vacancy->_workers = true;
        }

        return new VacancyResource($vacancy);
    }

    public function update(VacancyUpdateRequest $request, Vacancy $vacancy)
    {
        $this->authorize('update', $vacancy);

        $vacancy->update($request->all());

        return new VacancyResource($vacancy);
    }

    public function destroy(Vacancy $vacancy)
    {
        $this->authorize('delete', $vacancy);

        $vacancy->delete();

        return new VacancyResource($vacancy);
    }

    public function book(VacancyBookRequest $request)
    {
        $id = Auth::guard('api')->id();
        $user_id = $request->post('user_id');
        $vacancy_id = $request->post('vacancy_id');

        if ($id !== $user_id) $this->authorize('book', Vacancy::class);

        $vacancy = Vacancy::find($vacancy_id);
        $vacancy->workers()->attach($user_id);

        return response()->json([
            'succes' => true,
        ], 200);
    }

    public function unbook(VacancyBookRequest $request)
    {
        $id = Auth::guard('api')->id();
        $user_id = $request->post('user_id');
        $vacancy_id = $request->post('vacancy_id');

        if ($id !== $user_id) $this->authorize('book', Vacancy::class);
        
        $vacancy = Vacancy::find($vacancy_id);
        $vacancy->workers()->detach($user_id);

        return response()->json([
            'succes' => true,
        ], 200);
    }
}
