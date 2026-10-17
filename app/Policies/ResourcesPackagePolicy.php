<?php

namespace App\Policies;

use App\BusinessLogicLayer\UserRole\UserRoleManager;
use App\Models\Resource\ResourcesPackage;
use App\Models\User;

class ResourcesPackagePolicy {
    public function __construct(private UserRoleManager $userRoleManager) {}

    /**
     * Edit, update, submit or delete: the creator of the package, or an administrator.
     */
    public function manage(User $user, ResourcesPackage $package): bool {
        return $package->creator_user_id === $user->id || $this->userRoleManager->userHasAdminRole($user);
    }
}
