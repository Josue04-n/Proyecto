<?php

namespace App\Providers;

use App\Models\Cliente;
use App\Models\User;
use App\Policies\ClientePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar Policies manualmente
        Gate::policy(Cliente::class, ClientePolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        
        // Super Admin tiene acceso a todo
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}
