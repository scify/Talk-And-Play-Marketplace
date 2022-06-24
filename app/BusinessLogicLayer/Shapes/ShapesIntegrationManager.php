<?php


namespace App\BusinessLogicLayer\Shapes;


use App\BusinessLogicLayer\UserRole\UserRoleManager;
use App\Models\User;
use App\Repository\User\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShapesIntegrationManager {

    protected UserRepository $userRepository;
    protected UserRoleManager $userRoleManager;

    public function __construct(UserRoleManager $userRoleManager, UserRepository $userRepository) {
        $this->userRoleManager = $userRoleManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws Exception
     */
    public function createShapes(Request $request) {

        $response = Http::withHeaders([
            'X-Shapes-Key' => '7Msbb3w^SjVG%j',
            'Accept' => "application/json"
        ])->post('https://kubernetes.pasiphae.eu/shapes/asapa/auth/register', [
            'email' => $request['email'],
            'password' => $request['password'],
            'first_name' => 'Tester',
            'last_name' => 'Test',
        ]);
        if (!$response->ok()) {
            throw new Exception(json_decode($response->body())->error);
        }
        return $this->storeShapesUserLocally($request);
    }

    public function storeShapesUserLocally(Request $request) {
        $requestData = $request->all();
        $requestData['name'] = 'Talk_and_play_user';
        $user = $this->userRepository->create($requestData);
        $this->userRepository->update(['name' => 'Talk_and_play_user_' . $user->id], $user->id);
        $this->userRoleManager->assignShapesUserRoleTo($user);
        return $this->userRepository->find($user->id);
    }

    /**
     * @throws Exception
     */
    public function loginShapes(Request $request) {
        $response = Http::withHeaders([
            'X-Shapes-Key' => '7Msbb3w^SjVG%j',
            'Accept' => "application/json"
        ])->post('https://kubernetes.pasiphae.eu/shapes/asapa/auth/login', [
            'email' => $request['email'],
            'password' => $request['password'],
        ]);
        if (!$response->ok()) {
            throw new Exception(json_decode($response->body())->error);
        }
        return $response->json();
    }

    public function storeUserToken($user_id, $token) {
        $this->userRepository->update(['shapes_auth_token' => $token], $user_id);
    }

    public function findUserByEmail($email) {
        return $this->userRepository->findBy('email', $email);
    }

    public function updateSHAPESAuthTokenForUsers() {
        // get all shapes users
        // for each user, make the request to update their auth token
        // if the request results in error (meaning the token is already expired) log the user out
        // of the app, so that they have to login again

        $shapesUsers = $this->userRepository->getAllShapesUsers();
        foreach ($shapesUsers as $shapesUser) {
            try {
                $this->updateSHAPESAuthTokenForUser($shapesUser);
            } catch (Exception $e) {
                $this->userRepository->update(['logout' => true], $shapesUser->id);
            }
        }

    }

    /**
     * @throws Exception
     */
    public function updateSHAPESAuthTokenForUser(User $user) {
        $response = Http::withHeaders([
            'X-Shapes-Key' => '7Msbb3w^SjVG%j',
            'Accept' => "application/json",
            'X-Pasiphae-Auth' => $user->shapes_auth_token
        ])->post('https://kubernetes.pasiphae.eu/shapes/asapa/auth/token/refresh');
        if (!$response->ok()) {
            throw new Exception(json_decode($response->body())->error);
        }
        $response = $response->json();
        $new_token = $response['message'];
        // echo "\nUser: " . $user->id . "\t New token: " . $new_token . "\n";
        $this->userRepository->update(['shapes_auth_token' => $new_token],  $user->id);
    }

}
