<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vacancy\VacancyBookRequest;
use App\Http\Requests\Vacancy\VacancyStoreRequest;
use App\Http\Requests\Vacancy\VacancyUpdateRequest;
use App\Http\Resources\VacancyCollection;
use App\Http\Resources\VacancyResource;
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
                return $value;
            }
        });
        return new VacancyCollection($vacancies);
    }

    public function store(VacancyStoreRequest $request)
    {
        $vacancy = Vacancy::create($request->all());

        return new VacancyResource($vacancy);
    }

    public function show(Vacancy $vacancy)
    {
        $vacancy->load(['workers']);

        $vacancy->workers_booked = count($vacancy->workers()->get());
        $vacancy->_workers = true;

        return new VacancyResource($vacancy);
    }

    public function update(VacancyUpdateRequest $request, Vacancy $vacancy)
    {
        $vacancy->update($request->all());

        return new VacancyResource($vacancy);
    }

    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();

        return new VacancyResource($vacancy);
    }

    public function book(VacancyBookRequest $request)
    {
        $id = Auth::guard('api')->id();
        $user_id = $request->post('user_id');
        $vacancy_id = $request->post('vacancy_id');

        if ($id !== $user_id) abort(404);

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

        if ($id !== $user_id) abort(404, 'error');

        $vacancy = Vacancy::find($vacancy_id);
        $vacancy->workers()->detach($user_id);

        return response()->json([
            'succes' => true,
        ], 200);
    }
}
