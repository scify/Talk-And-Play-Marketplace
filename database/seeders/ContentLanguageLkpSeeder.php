<?php


namespace Database\Seeders;


use App\Repository\ContentLanguageLkpRepository;
use Illuminate\Database\Seeder;

class ContentLanguageLkpSeeder extends Seeder {

    protected ContentLanguageLkpRepository $contentLanguageLkpRepository;

    public function __construct(ContentLanguageLkpRepository $contentLanguageLkpRepository) {
        $this->contentLanguageLkpRepository = $contentLanguageLkpRepository;
    }


    public function run() {
        echo "\nRunning Content Language lkp Seeder...\n";

        $data = [
            ['id' => 1, 'name' => 'Ελληνικά', 'code' => 'el'],
            ['id' => 2, 'name' => 'English', 'code' => 'en'],
            ['id' => 3, 'name' => 'Deutsch', 'code' => 'de']
        ];

        foreach ($data as $datum) {
            $lang = $this->contentLanguageLkpRepository->updateOrCreate(['id' => $datum['id']], $datum);
            echo "\nAdded Content Language: " . $lang->name . "\n";
        }
    }
}
