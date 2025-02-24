<?php

namespace Database\Seeders;

use App\Repository\User\UserRole\UserRoleLkpRepository;
use Illuminate\Database\Seeder;

class UserRoleLkpTableSeeder extends Seeder {
    protected UserRoleLkpRepository $userRoleLkpRepository;

    public function __construct(UserRoleLkpRepository $userRoleLkpRepository) {
        $this->userRoleLkpRepository = $userRoleLkpRepository;
    }

    public function run() {
        if (config('app.env') !== 'testing') {
            echo "\nRunning User Role lkp Seeder...\n";
        }

        $data = [
            ['id'=> 1,  'name'=>'Platform Administrator'],
            ['id'=> 2,  'name'=>'Transcriber'],
            ['id'=> 3,  'name'=>'Shapes User'],
        ];

        foreach ($data as $userRoleLkp) {
            $role = $this->userRoleLkpRepository->updateOrCreate(['id' => $userRoleLkp['id']], $userRoleLkp);
            // if not in testing:
            if (config('app.env') !== 'testing') {
                echo "\nAdded User Role: " . $role->name . "\n";
            }
        }
    }
}
