<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{

    protected $fillable = ['vacancy_name', 'workers_amount', 'salary', 'organization_id'];

    protected $hidden = [
        'organization_id',
    ];

    public function organization()
    {
        return $this->belongsTo('App\Models\Organization');
    }

    public function company()
    { return $this->organization();}

    public function workers()
    {
        return $this->belongsToMany('App\Models\User', 'user_vacancies')->withPivot('user_id', 'vacancy_id');
    }
}
