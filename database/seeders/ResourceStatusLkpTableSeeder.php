<?php

namespace Database\Seeders;

use App\Repository\Resource\ResourceStatusLkpRepository;
use Illuminate\Database\Seeder;

class ResourceStatusLkpTableSeeder extends Seeder {
    protected ResourceStatusLkpRepository $resourceStatusLkpRepository;

    public function __construct(ResourceStatusLkpRepository $resourceStatusLkpRepository) {
        $this->resourceStatusLkpRepository = $resourceStatusLkpRepository;
    }


    public function run() {
        echo "\nRunning Resource status lkp Seeder...\n";

        $data = [
            ['id' => 1, 'name' => 'Created - pending approval', 'description' => 'The resource has been created and is pending approval'],
            ['id' => 2, 'name' => 'Approved', 'description' => 'The resource has been approved by an administrator'],
            ['id' => 3, 'name' => 'Rejected', 'description' => 'The resource has been rejected by an administrator']
        ];

        foreach ($data as $datum) {
            $status = $this->resourceStatusLkpRepository->updateOrCreate(['id' => $datum['id']], $datum);
            echo "\nAdded Resource Status: " . $status->name . "\n";
        }
    }
}
