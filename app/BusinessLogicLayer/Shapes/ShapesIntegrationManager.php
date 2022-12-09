<?php

namespace App\BusinessLogicLayer\Shapes;

use App\BusinessLogicLayer\UserRole\UserRoleManager;
use App\Models\User;
use App\Repository\User\UserRepository;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShapesIntegrationManager {
    protected UserRepository $userRepository;
    protected UserRoleManager $userRoleManager;
    protected $defaultHeaders = [
        'X-Shapes-Key' => null,
        'Accept' => 'application/json',
    ];
    protected $apiBaseUrl = 'https://kubernetes.pasiphae.eu/shapes/asapa/auth/';
    protected $datalakeAPIUrl;

    public function __construct(UserRoleManager $userRoleManager, UserRepository $userRepository) {
        $this->userRoleManager = $userRoleManager;
        $this->userRepository = $userRepository;
        $this->defaultHeaders['X-Shapes-Key'] = config('app.shapes_key');
        $this->datalakeAPIUrl = config('app.shapes_datalake_api_url');
    }

    public static function isEnabled(): bool {
        return config('app.shapes_datalake_api_url') !== null && config('app.shapes_datalake_api_url') !== '';
    }

    /**
     * @throws Exception
     */
    public function createShapes(Request $request) {
        $response = Http::withHeaders($this->defaultHeaders)
            ->post($this->apiBaseUrl . 'register', [
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
        $response = Http::withHeaders($this->defaultHeaders)
            ->post($this->apiBaseUrl . 'login', [
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
        $response = Http::withHeaders(array_merge($this->defaultHeaders, [
            'X-Pasiphae-Auth' => $user->shapes_auth_token,
        ]))->post($this->apiBaseUrl . 'token/refresh');
        if (!$response->ok()) {
            throw new Exception(json_decode($response->body())->error);
        }
        $response = $response->json();
        $new_token = $response['message'];
        // echo "\nUser: " . $user->id . "\t New token: " . $new_token . "\n";
        $this->userRepository->update(['shapes_auth_token' => $new_token], $user->id);
    }

    /**
     * @throws Exception
     */
    public function sendUsageDataToDatalakeAPI(array $data) {
        if (!isset($data['category_name'])) {
            $data['category_name'] = null;
        }
        if (!isset($data['duration'])) {
            $data['duration'] = null;
        }
        if (!isset($data['gameName'])) {
            $data['gameName'] = null;
        }
        if (!isset($data['gameType'])) {
            $data['gameType'] = null;
        }
        if (!isset($data['mistakes'])) {
            $data['mistakes'] = null;
        }
        if (!isset($data['parent_category_name'])) {
            $data['parent_category_name'] = null;
        }

        $response = Http::withHeaders([
            'X-Authorisation' => $data['token'],
            'Accept' => 'application/json',
        ])
            ->post($this->datalakeAPIUrl . $data['endpoint'], [
                'action' => $data['action'],
                'category_name' => $data['category_name'],
                'devId' => $data['devId'],
                'duration' => $data['duration'],
                'gameName' => $data['gameName'],
                'gameType' => $data['gameType'],
                'lang' => $data['lang'],
                'mistakes' => $data['mistakes'],
                'parent_category_name' => $data['parent_category_name'],
                'source' => 'desktop',
                'time' => Carbon::now()->format(DateTime::RFC3339),
                'version' => $data['version'],
            ]);
        if (!$response->ok()) {
            throw new Exception($response->body());
        }
        Log::info('SHAPES Desktop Datalake response: ' . json_encode($response->json()));

        return json_encode($response->json());
    }
}
