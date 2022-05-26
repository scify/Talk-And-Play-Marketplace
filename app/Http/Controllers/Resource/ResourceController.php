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
use App\Models\Resource\Resource;
use App\Models\User;
use App\Notifications\AcceptanceNotice;
use App\Notifications\AdminNotice;
use App\Notifications\RejectionNotice;
use App\Notifications\ReportNotice;
use App\Notifications\ReportResponseNotice;
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

                return redirect()->route($ret_route, $resourcePackage->id)
                    ->with('flash_message_success', __('messages.package-create-success'));
            } else {
                $resourcePackage = $manager->getResourcesPackageWithCoverCard($resource->resource_parent_id);
                return redirect()->route($ret_route, $resourcePackage->id)
                    ->with('flash_message_success', __('messages.card-create-success'));
            }
        } catch (Exception $e) {
            if ($resource && $resource->resource_parent_id == null)
                return redirect()->with('flash_message_failure', __('messages.package-create-failure'));
            return redirect()->with('flash_message_failure', __('messages.card-create-failure'));
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
            $request['status_id'] = ResourceStatusesLkp::CREATED_PENDING_APPROVAL;
            $ret = $this->resourceManager->updateResource($request, $id);

            return redirect()->back()->with('flash_message_success',  __('messages.update-success'));
        } catch (\Exception $e) {
            return redirect()->back()->with('flash_message_failure', __('messages.update-failure'));
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
            return redirect()->back()->with('flash_message_success',  __('messages.card-delete-success'));
        } catch (\Exception $e) {
            return redirect()->back()->with('flash_message_failure', __('messages.card-delete-failure'));
        }

        //
        #Manager get id of card
        #Manager calls repository
    }


    public function submit(int $id): \Illuminate\Http\RedirectResponse
    {

        $package = $this->resourcesPackageManager->getResourcesPackage($id);
        $redirect_route = $package->type_id===ResourceTypesLkp::COMMUNICATION ? 'communication_resources.index' : 'game_resources.index';
        $admins = $this->userManager->get_admin_users();
        $coverResourceCardName = $this->resourceManager->getResource($package->card_id)->name;
        Notification::send($admins, new AdminNotice($package, $coverResourceCardName));

        try {
            $this->resourcesPackageManager->submitResourcesPackage($id);
            return redirect()->route($redirect_route)->with('flash_message_success', __('messages.package-submit-success'));

        } catch (\Exception $e) {
            return redirect()->back()->with('flash_message_failure',  __('messages.package-submit-failure'));
        }

        //
        #Manager get id of card
        #Manager calls repository
    }


    public function approve(Request $request):\Illuminate\Http\RedirectResponse{
        $data = $request->all();
        $package = $this->resourcesPackageManager->getResourcesPackage($data['id']);
        $redirect_route = $package->type_id===ResourceTypesLkp::COMMUNICATION ? 'communication_resources.index' : 'game_resources.index';
        try {
            $this->resourcesPackageManager->approveResourcesPackage($data['id']);
            $coverResourceCardName = $this->resourceManager->getResource($package->card_id)->name;
            $creator = $this->userManager->getUser($package['creator_user_id']);
            Notification::send( $creator, new AcceptanceNotice($coverResourceCardName,$creator->name));
            return redirect()->route($redirect_route)->with('flash_message_success', __('messages.package-approve-success'));

        } catch (\Exception $e) {
            return redirect()->back()->with('flash_message_failure', __('messages.package-approve-failure'));
        }
    }

    public function reject(Request $request):\Illuminate\Http\RedirectResponse{
        $data = $request->all();
        $rejectionMessage = $data['rejection_comment'];
        $rejectionReason = $data['rejection_reason'];
        $package = $this->resourcesPackageManager->getResourcesPackage($data['id']);
        $redirect_route = $package->type_id===ResourceTypesLkp::COMMUNICATION ? 'communication_resources.index' : 'game_resources.index';

        try {
            $creator = $this->userManager->getUser($package['creator_user_id']);
            $this->resourcesPackageManager->rejectResourcesPackage($data['id']);
            $coverResourceCardName = $this->resourceManager->getResource($package->card_id)->name;
            Notification::send( $creator, new RejectionNotice($coverResourceCardName, $rejectionMessage, $rejectionReason, $creator->name));

            return redirect()->route($redirect_route)->with('flash_message_success', __('messages.package-reject-success'));

        } catch (\Exception $e) {
            return redirect()->back()->with('flash_message_failure',  __('messages.package-reject-failure'));
        }
    }

    public function report(Request $request):\Illuminate\Http\RedirectResponse{
        $data = $request->all();
        $reportComment = $data['report_comment'];
        $reportReason= $data['report_reason'];
        $package = $this->resourcesPackageManager->getResourcesPackage($data['id']);
        $coverResourceCardName = $this->resourceManager->getResource($package->card_id)->name;

        try {
            $creator = $this->userManager->getUser($package->creator_user_id);
            $reporter = Auth::user();
            $admins = $this->userManager->get_admin_users();
            $this->resourcesPackageManager->reportResourcesPackage($data['id'], $reporter['id'], $reportReason, $reportComment);
            Notification::send( $admins, new ReportNotice($package,$coverResourceCardName, $reportComment, $reportReason, $creator, $reporter));
            return redirect()->back()->with('flash_message_success','Reported successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('flash_message_failure', __('Failed to report'));
        }

    }

    public function respond(Request $request):\Illuminate\Http\RedirectResponse{
        $data = $request->all();
        $response = $data['response'];
        $package = $this->resourcesPackageManager->getResourcesPackage($data['id']);
        $coverResourceCardName = $this->resourceManager->getResource($package->card_id)->name;
        $reporting_user_id = $data['reporting_user_id'];
        try {
            $reporter = $this->userManager->getUser($reporting_user_id);
            Notification::send( $reporter, new ReportResponseNotice($coverResourceCardName, $response, $reporter->name));
            return redirect()->back()->with('flash_message_success','Responded successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('flash_message_failure', __('Failed to report'));
        }

    }




    public function my_packages()
    {
        try {
            $viewModel = $this->gameResourcesPackageManager->getGameResourcesPackageIndexPageVM();
            $viewModel->user_id_to_get_content = Auth::id();
            $viewModel->resourcesPackagesStatuses = [ResourceStatusesLkp::APPROVED, ResourceStatusesLkp::CREATED_PENDING_APPROVAL, ResourceStatusesLkp::REJECTED];
            $viewModel->isAdmin = $this->userManager->isAdmin(Auth::user());
            return view('resources_packages.my-packages')->with(
                ['viewModel' => $viewModel, 'user' => Auth::user()]);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }


    public function reported_packages()
    {
        try {
            $viewModel = $this->gameResourcesPackageManager->getGameResourcesPackageIndexPageVM();
            $viewModel->user_id_to_get_content = Auth::id();
            $viewModel->resourcesPackagesStatuses = [ResourceStatusesLkp::APPROVED];
            $viewModel->isAdmin = $this->userManager->isAdmin(Auth::user());
            return view('resources_packages.reported-packages')->with(
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
            return redirect()->back()->with('flash_message_failure', __('messages.package-delete-failure'));
        }
        return redirect()->back()->with('flash_message_success',   __('messages.package-delete-success'));
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

        try {
            $newPackage = $manager->storeResourcePackage($coverResource, $package->lang_id);
            $childrenWithParent = $manager->getChildrenCardsWithParent($package->card_id);
            foreach ($childrenWithParent as $child) {
                $this->resourceManager->cloneResource($child->id, $coverResource->id);
            }
            return redirect()->route($ret_route,$newPackage->id)->with('flash_message_success',  __('messages.package-clone-success'));
        }
        catch (\Exception $e){
            return redirect()->route($ret_route,$newPackage->id)->with('flash_message_failure',  __('messages.package-clone-failure'));
        }
    }

    public function getContentLanguages()
    {
        return $this->resourceManager->getContentLanguagesForResources();
    }

    public function getReports(Request $request)
    {

        $data = $request->all();
        if($data['type_ids']) {
            $type_ids = explode(",",$data['type_ids']);
        }
        else{
            $type_ids = [ResourceTypesLkp::COMMUNICATION];
        }
        $reportedPackagesWithMetadata = $this->resourcesPackageManager->getReportedPackages($type_ids,$data['lang_id']);
        return $reportedPackagesWithMetadata;

    }

}
