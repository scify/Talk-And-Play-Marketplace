<?php


namespace App\BusinessLogicLayer\Shapes;


use App\BusinessLogicLayer\UserRole\UserRoleManager;
use App\Models\UserRole\UserRoleLkp;
use App\Repository\Resource\ResourceTypesLkp;
use App\Repository\Shapes\UserTokensRepository;
use App\Repository\User\UserRepository;
use App\Repository\User\UserRole\UserRoleLkpRepository;
use App\Repository\User\UserRole\UserRolesLkp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\UnauthorizedException;

class ShapesIntegrationManager {

    protected UserRepository $userRepository;
    protected UserRoleManager $userRoleManager;
    protected UserTokensRepository $userTokensRepository;

    public function __construct(UserRoleManager $userRoleManager, UserRepository $userRepository,
                                UserTokensRepository $userTokensRepository) {
        $this->userRoleManager = $userRoleManager;
        $this->userRepository = $userRepository;
        $this->userTokensRepository = $userTokensRepository;
    }



    public function createShapes(Request $request)
    {

        $response = Http::withHeaders([
            'X-Shapes-Key' => '7Msbb3w^SjVG%j',
            'Accept' => "application/json"
        ])->post('https://kubernetes.pasiphae.eu/shapes/asapa/auth/register', [
            'email' => $request['email'],
            'password' => $request['password'],
            'first_name' => 'Tester',
            'last_name' => 'Test',
        ]);
        return $this->storeShapesUserLocally($request);
    }

    public function storeShapesUserLocally(Request $request){
        $requestData = $request->all();
        $requestData['name'] = 'Talk_and_play_user';
        $user = $this->userRepository->create($requestData);
        $this->userRepository->update(['name' => 'Talk_and_play_user_' . $user->id], $user->id);
        $this->userRoleManager->assignRegisteredUserRoleTo($user, UserRolesLkp::SHAPES_USER);
        return $this->userRepository->find($user->id);
    }

    public function loginShapes(Request $request)
    {
        $response = Http::withHeaders([
            'X-Shapes-Key' => '7Msbb3w^SjVG%j',
            'Accept' => "application/json"
        ])->post('https://kubernetes.pasiphae.eu/shapes/asapa/auth/login', [
            'email' => $request['email'],
            'password' => $request['password'],
        ]);
        if(!$response->ok()){
            abort(404);
        }
        return $response->json();
    }

    public function storeUserToken($user_id, $token){
        $storeArr = array(
            "user_id" => $user_id,
            "token" => $token
        );
        $this->userTokensRepository->create($storeArr);
    }

    public function findUserByEmail($email)
    {
        return $this->userRepository->findBy('email', $email);
    }

}
