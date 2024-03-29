<?php

namespace App\Http\Requests\Vacancy;

use Illuminate\Foundation\Http\FormRequest;

class VacancyStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vacancy_name' => 'required|string|max:100',
            'workers_amount' => 'required|numeric',
            'salary' => 'required|numeric',
            'organization_id' => 'required|numeric',
        ];
    }
}
