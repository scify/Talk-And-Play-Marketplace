<?php

namespace Database\Seeders;

use App\Repository\GameCategoriesLkpRepository;
use Illuminate\Database\Seeder;

class GameCategoriesSeeder extends Seeder {
    protected GameCategoriesLkpRepository $gameCategoriesLkpRepository;

    public function __construct(GameCategoriesLkpRepository $gameCategoriesLkpRepository) {
        $this->gameCategoriesLkpRepository = $gameCategoriesLkpRepository;
    }

    public function run() {
        if (config('app.env') !== 'testing') {
            echo "\nRunning Game Categories Seeder...\n";
        }

        $data = [
            [
                'id' => 1,
                'name' => 'Ερέθισμα - Αντίδραση',
                'code' => 'Response',
            ],
            [
                'id' => 2,
                'name' => 'Χρονική Αλληλουχία',
                'code' => 'Time',
            ],
            [
                'id' => 3,
                'name' => 'Βρες το Όμοιο',
                'code' => 'Similar',
            ],
        ];

        foreach ($data as $gameCategoriesLkp) {
            $resource = $this->gameCategoriesLkpRepository->updateOrCreate(['id' => $gameCategoriesLkp['id']], $gameCategoriesLkp);
            if (config('app.env') !== 'testing') {
                echo "\nAdded Resource: " . $resource->name . "\n";
            }
        }
    }
}
