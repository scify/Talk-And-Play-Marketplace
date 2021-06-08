<?php

namespace Database\Seeders;

use App\Repository\User\UserRole\UserRoleLkpRepository;
use Illuminate\Database\Seeder;

class UserRoleLkpTableSeeder extends Seeder
{
    protected UserRoleLkpRepository $userRoleLkpRepository;

    public function __construct(UserRoleLkpRepository $userRoleLkpRepository) {
        $this->userRoleLkpRepository = $userRoleLkpRepository;
    }


    public function run() {
        echo "\nRunning User Role lkp Seeder...\n";

        $data = [
            ['id'=> 1, 'name'=>'Platform Administrator'],
            ['id'=> 2, 'name'=>'Transcriber']
        ];

        foreach ($data as $userRoleLkp) {
            $role = $this->userRoleLkpRepository->updateOrCreate(['id' => $userRoleLkp['id']], $userRoleLkp);
            echo "\nAdded User Role: " . $role->name . "\n";
        }
    }
}
