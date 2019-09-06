<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'first_name', 'email', 'password', 'last_name', 'country', 'city', 'phone', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'api_token', 'pivot',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function generateToken()
    {
        $this->api_token = Str::random(60);
        $this->save();

        return $this->api_token;
    }

    public function organization(){
        return $this->hasMany('App\Models\Organization');
    }

    public function vacancies(){
        return $this->belongsToMany('App\Models\Vacancy', 'user_vacancies');
    }
}
