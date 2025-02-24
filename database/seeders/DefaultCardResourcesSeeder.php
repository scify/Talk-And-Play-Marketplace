<?php

//
//
// namespace Database\Seeders;
//
//
// use App\Repository\Resource\ContentLanguagesLkp;
// use App\Repository\Resource\ResourceRepository;
// use App\Repository\Resource\ResourceStatusesLkp;
// use App\Repository\Resource\ResourceTypesLkp;
// use Illuminate\Database\Seeder;
//
// class DefaultCardResourcesSeeder extends Seeder {
//
//    protected ResourceRepository $resourceRepository;
//
//    public function __construct(ResourceRepository $resourceRepository) {
//        $this->resourceRepository = $resourceRepository;
//    }
//
//
//    public function run() {
//        echo "\nRunning Default Communication Card Resources Seeder...\n";
//
//        $data = [
//            [
//                'id' => 589,
//                'name' => 'Κάρτα 4',
//                'resource_parent_id' => 585,
//                'img_path' => 'resources/img/589_dressing4_2021-09-22_09_23_22.png',
//                'audio_path' => NULL,
//                'creator_user_id' => 1
//            ],
//            [
//            'id' => 588,
//            'name' => 'Κάρτα 3',
//            'resource_parent_id' => 585,
//            'img_path' => 'resources/img/588_dressing3_2021-09-22_09_23_09.png',
//            'audio_path' => NULL,
//            'creator_user_id' => 1
//            ],
//            [
//                'id' => 587,
//                'name' => 'Κάρτα 3',
//                'resource_parent_id' => 585,
//                'img_path' => 'resources/img/587_dressing2_2021-09-22_09_22_52.png',
//                'audio_path' => NULL,
//                'creator_user_id' => 1
//            ],
//            [
//                'id' => 586,
//                'name' => 'Κάρτα 3',
//                'resource_parent_id' => 585,
//                'img_path' => 'resources/img/586_dressing1_2021-09-22_09_22_40.png',
//                'audio_path' => NULL,
//                'creator_user_id' => 1
//            ],
//            [
//                'id' => 585,
//                'name' => 'Ντύσιμο',
//                'resource_parent_id' => NULL,
//                'img_path' => 'resources/img/585_dressing4_2021-09-22_09_22_26.png',
//                'audio_path' => NULL,
//                'creator_user_id' => 1
//            ],
//            [
//                'id' => 508,
//                'name' => 'Μικρό Κίτρινο',
//                'resource_parent_id' => 508,
//                'img_path' => 'resources/img/585_dressing4_2021-09-22_09_22_26.png',
//                'audio_path' => NULL,
//                'creator_user_id' => 1
//            ],
//            [
//                'id' => 512,
//                'name' => 'Μικρό Μπλέ',
//                'resource_parent_id' => 508,
//                'img_path' => 'resources/img/585_dressing4_2021-09-22_09_22_26.png',
//                'audio_path' => NULL,
//                'creator_user_id' => 1
//            ],
//            [
//                'id' => 512,
//                'name' => 'Μικρό Μπλέ',
//                'resource_parent_id' => 508,
//                'img_path' => 'resources/img/585_dressing4_2021-09-22_09_22_26.png',
//                'audio_path' => NULL,
//                'creator_user_id' => 1
//            ],
//            [
//                'id' => 105,
//                'name' => 'Μικρό Μπλέ',
//                'resource_parent_id' => 508,
//                'img_path' => 'resources/img/585_dressing4_2021-09-22_09_22_26.png',
//                'audio_path' => NULL,
//                'creator_user_id' => 1
//            ],
//
//        ];
//
//        foreach ($data as $userRoleLkp) {
//            $resource = $this->resourceRepository->updateOrCreate(['id' => $userRoleLkp['id']], $userRoleLkp);
//            echo "\nAdded Resource: " . $resource->name . "\n";
//        }
//    }
// }
