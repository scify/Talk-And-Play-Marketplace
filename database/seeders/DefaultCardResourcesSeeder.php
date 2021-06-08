<?php


namespace Database\Seeders;


use App\Repository\Resource\ContentLanguagesLkp;
use App\Repository\Resource\ResourceRepository;
use App\Repository\Resource\ResourceStatusesLkp;
use App\Repository\Resource\ResourceTypesLkp;
use Illuminate\Database\Seeder;

class DefaultCardResourcesSeeder extends Seeder {

    protected ResourceRepository $resourceRepository;

    public function __construct(ResourceRepository $resourceRepository) {
        $this->resourceRepository = $resourceRepository;
    }


    public function run() {
        echo "\nRunning Default Communication Card Resources Seeder...\n";

        $data = [
            [
                'id' => 1,
                'name' => 'Συναισθήματα',
                'status_id' => ResourceStatusesLkp::APPROVED,
                'lang_id' => ContentLanguagesLkp::GREEK,
                'creator_user_id' => 1,
                'admin_user_id' => 1,
                'img_path' => 'storage/resources/img/happiness.png',
                'audio_path' => 'storage/resources/audio/happiness_el.mp3',
                'resource_parent_id' => null,
                'type_id' => ResourceTypesLkp::COMMUNICATION
            ],
            [
                'id' => 2,
                'name' => 'Θυμός',
                'status_id' => ResourceStatusesLkp::APPROVED,
                'lang_id' => ContentLanguagesLkp::GREEK,
                'creator_user_id' => 1,
                'admin_user_id' => 1,
                'img_path' => 'storage/resources/img/anger.png',
                'audio_path' => 'storage/resources/audio/anger_el.mp3',
                'resource_parent_id' => 1,
                'type_id' => ResourceTypesLkp::COMMUNICATION
            ],
            [
                'id' => 3,
                'name' => 'Λύπη',
                'status_id' => ResourceStatusesLkp::APPROVED,
                'lang_id' => ContentLanguagesLkp::GREEK,
                'creator_user_id' => 1,
                'admin_user_id' => 1,
                'img_path' => 'storage/resources/img/sadness.png',
                'audio_path' => 'storage/resources/audio/sadness_el.mp3',
                'resource_parent_id' => 1,
                'type_id' => ResourceTypesLkp::COMMUNICATION
            ],
            [
                'id' => 4,
                'name' => 'Χαρά',
                'status_id' => ResourceStatusesLkp::APPROVED,
                'lang_id' => ContentLanguagesLkp::GREEK,
                'creator_user_id' => 1,
                'admin_user_id' => 1,
                'img_path' => 'storage/resources/img/happiness.png',
                'audio_path' => 'storage/resources/audio/happiness_el.mp3',
                'resource_parent_id' => 1,
                'type_id' => ResourceTypesLkp::COMMUNICATION
            ],
            [
                'id' => 5,
                'name' => 'Αηδία',
                'status_id' => ResourceStatusesLkp::APPROVED,
                'lang_id' => ContentLanguagesLkp::GREEK,
                'creator_user_id' => 1,
                'admin_user_id' => 1,
                'img_path' => 'storage/resources/img/disgust.png',
                'audio_path' => 'storage/resources/audio/disgust_el.mp3',
                'resource_parent_id' => 1,
                'type_id' => ResourceTypesLkp::COMMUNICATION
            ],
            [
                'id' => 6,
                'name' => 'Φόβος',
                'status_id' => ResourceStatusesLkp::APPROVED,
                'lang_id' => ContentLanguagesLkp::GREEK,
                'creator_user_id' => 1,
                'admin_user_id' => 1,
                'img_path' => 'storage/resources/img/terror.png',
                'audio_path' => 'storage/resources/audio/terror_el.mp3',
                'resource_parent_id' => 1,
                'type_id' => ResourceTypesLkp::COMMUNICATION
            ]
        ];

        foreach ($data as $userRoleLkp) {
            $resource = $this->resourceRepository->updateOrCreate(['id' => $userRoleLkp['id']], $userRoleLkp);
            echo "\nAdded Resource: " . $resource->name . "\n";
        }
    }
}
