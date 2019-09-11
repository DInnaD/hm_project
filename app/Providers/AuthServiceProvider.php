<?php

namespace App\Providers;

use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\OrganizationPolicy;
use App\Policies\UserPolicy;
use App\Policies\VacancyPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Organization::class => OrganizationPolicy::class,
        User::class => UserPolicy::class,
        Vacancy::class => VacancyPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
