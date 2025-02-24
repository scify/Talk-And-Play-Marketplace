<?php

//
//
// namespace Database\Seeders;
//
//
// use App\Repository\Resource\ContentLanguagesLkp;
// use App\Repository\Resource\ResourceRepository;
// use App\Repository\Resource\ResourcesPackageRepository;
// use App\Repository\Resource\ResourceStatusesLkp;
// use App\Repository\Resource\ResourceTypesLkp;
// use Illuminate\Database\Seeder;
//
// class DefaultPackagesResourcesSeeder extends Seeder {
//
//    protected ResourcesPackageRepository $resourcesPackageRepository;
//
//    public function __construct(ResourcesPackageRepository $resourcesPackageRepository) {
//        $this->resourcePackageRepository = $resourcesPackageRepository;
//    }
// # id, card_id, status_id, lang_id, creator_user_id, admin_user_id, created_at, updated_at, deleted_at, type_id
//
//
//    public function run() {
//        echo "\nRunning Default Communication Card Resources Seeder...\n";
//
//        $data = [
//            [
//                'id' => 160,
//                'card_id' => 585,
//                'status_id' => 2,
//                'lang_id' => 1,
//                'creator_user_id' => 1,
//            ],
//            [
//                'id' => 159,
//                'card_id' => 580,
//                'status_id' => 2,
//                'lang_id' => 1,
//                'creator_user_id' => 1,
//            ],
//            [
//                'id' => 154,
//                'card_id' => 559,
//                'status_id' => 2,
//                'lang_id' => 1,
//                'creator_user_id' => 1,
//            ],
//            [
//                'id' => 152,
//                'card_id' => 552,
//                'status_id' => 2,
//                'lang_id' => 1,
//                'creator_user_id' => 1,
//            ],
//            [
//                'id' => 149,
//                'card_id' => 534,
//                'status_id' => 2,
//                'lang_id' => 1,
//                'creator_user_id' => 1,
//            ],
//            [
//                'id' => 146,
//                'card_id' => 519,
//                'status_id' => 2,
//                'lang_id' => 1,
//                'creator_user_id' => 1,
//            ],
//            [
//                'id' => 145,
//                'card_id' => 514,
//                'status_id' => 2,
//                'lang_id' => 1,
//                'creator_user_id' => 1,
//            ],
//            [
//                'id' => 143,
//                'card_id' => 500,
//                'status_id' => 2,
//                'lang_id' => 1,
//                'creator_user_id' => 1,
//            ],
//            [
//                'id' => 142,
//                'card_id' => 495,
//                'status_id' => 2,
//                'lang_id' => 1,
//                'creator_user_id' => 1,
//            ],
//            [
//                'id' => 141,
//                'card_id' => 491,
//                'status_id' => 2,
//                'lang_id' => 1,
//                'creator_user_id' => 1,
//            ],
//            [
//                'id' => 140,
//                'card_id' => 486,
//                'status_id' => 2,
//                'lang_id' => 1,
//                'creator_user_id' => 1,
//            ],
//            [
//                'id' => 111,
//                'card_id' => 409,
//                'status_id' => 2,
//                'lang_id' => 2,
//                'creator_user_id' => 1,
//            ],
//            [
//                'id' => 144,
//                'card_id' => 508,
//                'status_id' => 2,
//                'lang_id' => 1,
//                'creator_user_id' => 1,
//            ],
//            [
//                'id' => 110,
//                'card_id' => 405,
//                'status_id' => 2,
//                'lang_id' => 1,
//                'creator_user_id' => 1,
//            ],
//            [
//                'id' => 109,
//                'card_id' => 399,
//                'status_id' => 2,
//                'lang_id' => 2,
//                'creator_user_id' => 1,
//            ],
//            [
//                'id' => 1,
//                'card_id' => 148,
//                'status_id' => 2,
//                'lang_id' => 1,
//                'creator_user_id' => 1,
//            ],
//        ];
//
//        foreach ($data as $userRoleLkp) {
//            $resourcePackage = $this->resourcesPackageRepository->updateOrCreate(['id' => $userRoleLkp['id']], $userRoleLkp);
//            echo "\nAdded Resource: " . $resourcePackage->id . "\n";
//        }
//    }
// }
