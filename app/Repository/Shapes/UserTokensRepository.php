<?php


namespace App\Repository\Shapes;
use App\Models\Shapes\UserToken;
use App\Repository\Repository;


class UserTokensRepository extends Repository {
    protected array $defaultRelationships = ['creator'];

    function getModelClassName(): string
    {
        return UserToken::class;
    }
}
