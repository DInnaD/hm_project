<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{

    protected $fillable = ['title', 'country', 'city', 'user_id'];

    protected $hidden = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function creator()
    {
        return $this->user();
    }

    public function vacancies(){
        return $this->hasMany('App\Models\Vacancy');
    }
}
