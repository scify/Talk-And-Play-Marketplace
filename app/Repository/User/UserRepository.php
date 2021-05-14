<?php


namespace App\Repository\User;

use App\Models\User;
use App\Repository\Repository;
use App\Repository\User\UserRole\UserRolesLkp;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository extends Repository {

    function getModelClassName() {
        return User::class;
    }

    public function create(array $data) {
        return parent::create([
            'name' => $data["name"],
            'email' => $data["email"],
            'password' => Hash::make($data["password"]),
        ]);
    }

    public function getUsersWithAdminRoleStatus(int $userIdToExclude): Collection {
        return collect(DB::select('select u.id, u.name, u.email,
                                    (
                                    select ur.id from user_roles ur
                                    where ur.user_id = u.id
                                    and ur.role_id = ?
                                    and ur.deleted_at is null
                                    ) is not null as is_admin from users u
                                    where u.id <> ?
                            and u.deleted_at is null
                            group by u.id, is_admin;', [UserRolesLkp::ADMIN, $userIdToExclude]));
    }

    public function delete($id) {
        $user = $this->find($id);
        $user->email = $user->email . '_deleted_' . Carbon::now()->timestamp;
        $user->save();
        return parent::delete($id);
    }
}
