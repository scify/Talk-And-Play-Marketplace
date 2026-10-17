<?php

namespace App\Providers;

use App\BusinessLogicLayer\UserRole\UserRoleManager;
use App\Models\Resource\ResourcesPackage;
use App\Policies\ResourcesPackagePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\App;

class AuthServiceProvider extends ServiceProvider {
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        ResourcesPackage::class => ResourcesPackagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot() {
        $userRoleManager = App::make(UserRoleManager::class);
        $userRoleManager->registerUserPolicies();
    }
}
