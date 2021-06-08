<?php

namespace Database\Seeders;

use App\Repository\Resource\ResourceTypeLkpRepository;
use Illuminate\Database\Seeder;

class ResourceTypeLkpTableSeeder extends Seeder {
    protected ResourceTypeLkpRepository $repository;

    public function __construct(ResourceTypeLkpRepository $repository) {
        $this->repository = $repository;
    }


    public function run() {
        echo "\nRunning Resource status lkp Seeder...\n";

        $data = [
            ['id' => 1, 'name' => 'Communication', 'description' => 'Communication resource'],
            ['id' => 2, 'name' => 'Game', 'description' => 'Game resource']
        ];

        foreach ($data as $datum) {
            $type = $this->repository->updateOrCreate(['id' => $datum['id']], $datum);
            echo "\nAdded Resource Type: " . $type->name . "\n";
        }
    }
}
