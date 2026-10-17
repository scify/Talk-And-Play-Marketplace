<?php

namespace App\Policies;

use App\BusinessLogicLayer\UserRole\UserRoleManager;
use App\Models\Resource\ResourcesPackage;
use App\Models\User;
use App\Repository\Resource\ResourceStatusesLkp;

class ResourcesPackagePolicy {
    public function __construct(private UserRoleManager $userRoleManager) {}

    /**
     * Edit, update, submit or delete: the creator of the package, or an administrator.
     */
    public function manage(User $user, ResourcesPackage $package): bool {
        return $package->creator_user_id === $user->id || $this->userRoleManager->userHasAdminRole($user);
    }

    /**
     * Download or clone: any user for an approved package; otherwise only those who can manage it.
     */
    public function view(User $user, ResourcesPackage $package): bool {
        return (int) $package->status_id === ResourceStatusesLkp::APPROVED || $this->manage($user, $package);
    }
}
