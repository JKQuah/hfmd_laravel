<?php

namespace App\Providers;

use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define role as admin
        Gate::define('admin', function ($user) {
            return $user->role == $user::ROLE_ADMIN;
        });
        
        // Define role as staff who can access both staff and admin 
        Gate::define('staff', function ($user) {
            return $user->role == $user::ROLE_ADMIN || $user->role == $user::ROLE_STAFF;
        });

        // Define role as public
        Gate::define('public', function ($user) {
            return $user->role == $user::ROLE_PUBLIC && $user->status == $user::STATUS_ACTIVE;
        });

    }
}
