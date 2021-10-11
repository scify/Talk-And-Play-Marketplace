<?php

namespace App\Http\Controllers\Resource;

use App\BusinessLogicLayer\Resource\ResourceManager;
use App\BusinessLogicLayer\Resource\ResourcesPackageManager;
use App\BusinessLogicLayer\Resource\CommunicationResourcesPackageManager;
use App\BusinessLogicLayer\Resource\SimilarityGameResourcesPackageManager;
use App\BusinessLogicLayer\Resource\TimeGameResourcesPackageManager;
use App\BusinessLogicLayer\Resource\GameResourcesPackageManager;
use App\BusinessLogicLayer\Resource\ResponseGameResourcesPackageManager;
use App\BusinessLogicLayer\User\UserManager;
use App\Models\User;
use App\Notifications\AdminNotice;
use App\Repository\Resource\ResourceStatusesLkp;
use App\Repository\Resource\ResourceTypeLkpRepository;
use App\Repository\Resource\ResourceTypesLkp;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repository\User\UserRepository;
use Illuminate\Support\Facades\Notification;

class ResourceController extends Controller
{

    protected ResourceManager $resourceManager;
    protected ResourcesPackageManager $resourcesPackageManager;
    protected SimilarityGameResourcesPackageManager $similarityGameResourcesPackageManager;
    protected TimeGameResourcesPackageManager $timeGameResourcesPackageManager;
    protected ResponseGameResourcesPackageManager $responseGameResourcesPackageManager;
    protected CommunicationResourcesPackageManager $communicationResourcesPackageManager;
    protected GameResourcesPackageManager $gameResourcesPackageManager;
    protected UserManager $userManager;

    public function __construct(ResourceManager $resourceManager, ResourcesPackageManager $resourcesPackageManager,
                                CommunicationResourcesPackageManager $communicationResourcesPackageManager,
                                SimilarityGameResourcesPackageManager $similarityGameResourcesPackageManager,
                                TimeGameResourcesPackageManager $timeGameResourcesPackageManager,
                                ResponseGameResourcesPackageManager $responseGameResourcesPackageManager,
                                GameResourcesPackageManager $gameResourcesPackageManager,
                                UserManager  $userManager)
    {
        $this->resourceManager = $resourceManager;
        $this->resourcesPackageManager = $resourcesPackageManager;
        $this->similarityGameResourcesPackageManager = $similarityGameResourcesPackageManager;
        $this->communicationResourcesPackageManager = $communicationResourcesPackageManager;
        $this->responseGameResourcesPackageManager = $responseGameResourcesPackageManager;
        $this->timeGameResourcesPackageManager = $timeGameResourcesPackageManager;
        $this->gameResourcesPackageManager = $gameResourcesPackageManager;
        $this->userManager = $userManager;
    }





    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string|max:100',
            'image' => 'mimes:jpg,png,jpeg|required|file|between:3,1000|nullable',
            'type_id' => 'required'
        ]);

        $type_id = intval($request->type_id);
        /*switch($type_id){
            case ResourceTypesLkp::COMMUNICATION:

        }
        */
        if ($type_id === ResourceTypesLkp::COMMUNICATION) {
            $this->validate($request, ['sound' => 'required|file|between:10,1000|nullable']);
            $manager = $this->communicationResourcesPackageManager;
            $ret_route = "communication_resources.edit";
        } else if ($type_id === ResourceTypesLkp::SIMILAR_GAME) {
            $manager = $this->similarityGameResourcesPackageManager;
            $ret_route = "game_resources.edit";
        } else if ($type_id === ResourceTypesLkp::TIME_GAME) {
            $manager = $this->timeGameResourcesPackageManager;
            $ret_route = "game_resources.edit";
        } else if ($type_id === ResourceTypesLkp::RESPONSE_GAME) {
            $manager = $this->responseGameResourcesPackageManager;
            $ret_route = "game_resources.edit";
        } else {
            throw(new \ValueError("Type not supported"));
        }
        try {
            $resource = $this->resourceManager->storeResource($request);
            if ($resource->resource_parent_id == null) {
                $resourcePackage = $manager->storeResourcePackage($resource, $request['lang']);
            } else {
                $resourcePackage = $manager->getResourcesPackageWithCoverCard($resource->resource_parent_id);
            }
            $redirect_id = $resourcePackage->id;


            return redirect()->route($ret_route, $redirect_id)
                ->with('flash_message_success', 'The resource has been successfully created');
        } catch (Exception $e) {
            return redirect()->with('flash_message_failure', 'Failure - resource card has not been added');
        }


    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse#after submit, (action-route submit button directs here)
    {
        $this->validate($request, [
            'name' => 'string|max:100',
            'image' => 'mimes:jpg,png|file|between:3,1000|nullable',
            'type_id' => 'required'
        ]);


//        $id = intval($request->id);
        $type_id = intval($request->type_id);
        if ($type_id === ResourceTypesLkp::COMMUNICATION) {
            $this->validate($request, ['sound' => 'mimes:mp3|file|between:10,2000|nullable']);
            $ret_route = "communication_resources.edit";
        } else if (in_array($type_id, [
            ResourceTypesLkp::SIMILAR_GAME,
            ResourceTypesLkp::TIME_GAME,
            ResourceTypesLkp::RESPONSE_GAME
        ])) {
            $ret_route = "game_resources.edit";
        } else {
            throw(new \ValueError("Type not supported"));
        }
        try {
            $ret = $this->resourceManager->updateResource($request, $id);
            #$redirect_id = $ret['resource_parent_id'] ?: $ret->id;
            #$resourcePackage = $this->resourcesPackageManager->getResourcesPackage ();
            #return redirect()->route($ret_route, $resourcePackage->id)->with('flash_message_success', 'The resource package has been successfully updated');
            return redirect()->back()->with('flash_message_success', 'The resource package has been successfully updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('flash_message_failure', 'The resource package has not been updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */

    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $this->resourceManager->destroyResource($id);
            return redirect()->back()->with('flash_message_success', 'Success! The resource has been deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('flash_message_failure', 'Warning! The resource has not been deleted');
        }

        //
        #Manager get id of card
        #Manager calls repository
    }


    public function submit(int $id): \Illuminate\Http\RedirectResponse
    {
        return $this->approve($id); //TODO: uncomment to enable pending package approval by admin
        $package = $this->resourcesPackageManager->getResourcesPackage($id);
        $redirect_route = $package->type_id===ResourceTypesLkp::COMMUNICATION ? 'communication_resources.index' : 'game_resources.index';
        $admins = $this->userManager->get_admin_users();
        $coverResourceCardName = $this->resourceManager->getResource($package->card_id)->name;
        Notification::send($admins, new AdminNotice($package, $coverResourceCardName));

        try {
            $this->resourcesPackageManager->submitResourcesPackage($id);
            return redirect()->route($redirect_route)->with('flash_message_success', 'Success! The resource package has been submitted');

        } catch (\Exception $e) {
            return redirect()->back()->with('flash_message_failure', 'Warning! The resource package has not been submitted');
        }

        //
        #Manager get id of card
        #Manager calls repository
    }


    public function approve(int $id):\Illuminate\Http\RedirectResponse{
        $package = $this->resourcesPackageManager->getResourcesPackage($id);
        $redirect_route = $package->type_id===ResourceTypesLkp::COMMUNICATION ? 'communication_resources.index' : 'game_resources.index';
        try {
            $this->resourcesPackageManager->approveResourcesPackage($id);
            return redirect()->route($redirect_route)->with('flash_message_success', 'Success! The resource package has been approved');

        } catch (\Exception $e) {
            return redirect()->back()->with('flash_message_failure', 'Warning! The resource package has not been approved');
        }
    }

    public function reject(Request $request):\Illuminate\Http\RedirectResponse{
        $data = $request->all();
        $package = $this->resourcesPackageManager->getResourcesPackage($data['id']);
        $rejectionMessage = $data['rejection_message'];
        $redirect_route = $package->type_id===ResourceTypesLkp::COMMUNICATION ? 'communication_resources.index' : 'game_resources.index';

        try {
            $this->resourcesPackageManager->rejectResourcesPackage($data['id']);
            return redirect()->route($redirect_route)->with('flash_message_success', 'Success! The resource package has been rejected');

        } catch (\Exception $e) {
            return redirect()->back()->with('flash_message_failure', 'Warning! The resource package has not been rejected');
        }
    }



    public function my_packages()
    {
        try {
            $viewModel = $this->gameResourcesPackageManager->getGameResourcesPackageIndexPageVM();
            $viewModel->user_id_to_get_content = Auth::id();
            $viewModel->resourcesPackagesStatuses = [ResourceStatusesLkp::APPROVED];
            $viewModel->isAdmin = $this->userManager->isAdmin(Auth::user());
            return view('resources_packages.my-packages')->with(
                ['viewModel' => $viewModel, 'user' => Auth::user()]);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }

    public function approve_pending_packages()
    {
        try {
            $userId = Auth::id();
            $adminIds = $this->userManager->get_admin_users()->map(
                function($admin){
                    return $admin->id;
                }
            );

            if(!$adminIds->contains($userId)){
                return response('Unauthorized.', 401);
            }


            $viewModel = $this->gameResourcesPackageManager->getGameResourcesPackageIndexPageVM();

            $viewModel->resourcesPackagesStatuses = [ResourceStatusesLkp::CREATED_PENDING_APPROVAL];
            $viewModel->user_id_to_get_content = null;

            $viewModel->isAdmin = $this->userManager->isAdmin(Auth::user());
            return view('resources_packages.approve-pending-packages')->with(
                ['viewModel' => $viewModel, 'user' => Auth::user()]);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }


    public function delete_package($package_id)
    {
        try {
            $this->resourcesPackageManager->destroyResourcesPackage($package_id);
        }
        catch (ModelNotFoundException $e) {
            abort(404);
        }
        catch (\Exception $e) {
            return redirect()->back()->with('flash_message_failure', 'Warning! The resource package has not been deleted');
        }
        return redirect()->back()->with('flash_message_success',  'Success! The resource package has been deleted');
    }


    public function clone_package($package_id){

        $package = $this->resourcesPackageManager->getResourcesPackage($package_id);
        $coverResource = $this->resourceManager->cloneResource($package->card_id, null);
        if ($package->type_id === ResourceTypesLkp::COMMUNICATION) {
            $manager = $this->communicationResourcesPackageManager;
            $ret_route = "communication_resources.edit";
        } else if ($package->type_id  === ResourceTypesLkp::SIMILAR_GAME) {
            $manager = $this->similarityGameResourcesPackageManager;
            $ret_route = "game_resources.edit";
        } else if ($package->type_id  === ResourceTypesLkp::TIME_GAME) {
            $manager = $this->timeGameResourcesPackageManager;
            $ret_route = "game_resources.edit";
        } else if ($package->type_id  === ResourceTypesLkp::RESPONSE_GAME) {
            $manager = $this->responseGameResourcesPackageManager;
            $ret_route = "game_resources.edit";
        } else {
            throw(new \ValueError("Type not supported"));
        }

        $newPackage = $manager->storeResourcePackage($coverResource, $package->lang_id);
        $childrenWithParent = $manager->getChildrenCardsWithParent($package->card_id);
        foreach($childrenWithParent as $child){
            $this->resourceManager->cloneResource($child->id, $coverResource->id);
        }
        return redirect()->route($ret_route,$newPackage->id)->with('flash_message_success',  'Success! The resource package has been copied');
    }



    public function getContentLanguages()
    {
        return $this->resourceManager->getContentLanguagesForResources();
    }

}
