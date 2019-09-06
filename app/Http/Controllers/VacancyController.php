<?php

namespace App\Http\Controllers;

use App\Models\user_vacancy;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacancyController extends Controller
{
    public function index(Request $request)
    {
        $only_active = $request->get('only_active');

        $vacancies = Vacancy::withCount(['workers AS workers_booked'])->get();
        $vacancies->each(function($value){
            $value->organization = $value->company->title;
            $value->makeHidden('company');
        });

        if ($only_active != "false") {
            foreach ($vacancies as $key => $vacancy) {
                if ($vacancy->workers_booked >= $vacancy->workers_amount) {
                    unset($vacancies[$key]);
                }
            }
        }

        return response()->json([
            'succes' => true,
            'data' => $vacancies
        ], 200);
    }

    public function store(Request $request)
    {
        $vacancy = Vacancy::create($request->all());
        return response()->json([
            'succes' => true,
            'data' => $vacancy
        ], 201);
    }

    public function show(Vacancy $vacancy)
    {
        $vacancy->load(['workers'])->makeHidden('company');
        $vacancy->organization = $vacancy->company->title;
        $vacancy->workers_booked = count($vacancy->workers()->get());
        $vacancy->status = 'active';
        if ($vacancy->workers_amount <= $vacancy->workers_booked) {
            $vacancy->status = 'closed';
        }
        return response()->json([
            'succes' => true,
            'data' => $vacancy
        ], 200);
    }

    public function update(Request $request, Vacancy $vacancy)
    {
        $vacancy->update($request->all());
        return response()->json([
            'succes' => true,
            'data' => $vacancy
        ], 200);
    }

    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();
        return response()->json([
            'succes' => true,
            'data' => $vacancy
        ], 204);
    }

    public function book(Vacancy $vacancy)
    {
        $user_id = Auth::guard('api')->id();
        $vacancy_id = $vacancy->id;
        $user_vacancy = user_vacancy::create(['user_id' => $user_id, 'vacancy_id' => $vacancy_id]);
        return response()->json([
            'succes' => true,
            'data' => $user_vacancy
        ], 200);
    }

    public function unbook(Vacancy $vacancy)
    {
        $user_id = Auth::guard('api')->id();
        $vacancy_id = $vacancy->id;
        $user_vacancy = user_vacancy::where(['user_id' => $user_id, 'vacancy_id' => $vacancy_id])->first();
        return response()->json([
            'succes' => true,
            'data' => $user_vacancy->delete()
        ], 204);
    }
}
