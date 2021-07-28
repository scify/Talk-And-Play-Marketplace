<?php

namespace App\Http\Controllers\Resource;

use App\BusinessLogicLayer\Resource\ResourceManager;
use App\BusinessLogicLayer\Resource\ResourcesPackageManager;
use App\BusinessLogicLayer\Resource\CommunicationResourcesPackageManager;
use App\BusinessLogicLayer\Resource\SimilarityGameResourcesPackageManager;
use App\Repository\Resource\ResourceTypesLkp;
use App\Http\Controllers\Controller;
use App\Models\Resource\Resource;
use App\ViewModels\CreateEditResourceVM;
use Illuminate\Collections\ItemNotFoundException;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class ResourceController extends Controller
{

    protected ResourceManager $ResourceManager;
    protected ResourcesPackageManager $ResourcesPackageManager;
    protected SimilarityGameResourcesPackageManager $SimilarityGameResourcesPackageManager;
    protected CommunicationResourcesPackageManager $CommunicationResourcesPackageManager;

    public function __construct(ResourceManager $ResourceManager, ResourcesPackageManager $ResourcesPackageManager,
                                CommunicationResourcesPackageManager $CommunicationResourcesPackageManager,
                                SimilarityGameResourcesPackageManager $SimilarityGameResourcesPackageManager)
    {
        $this->ResourceManager = $ResourceManager;
        $this->ResourcesPackageManager = $ResourcesPackageManager;
        $this->SimilarityGameResourcesPackageManager = $SimilarityGameResourcesPackageManager;
        $this->CommunicationResourcesPackageManager = $CommunicationResourcesPackageManager;
    }


    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('resources.index');
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
            'image' => 'required|file|between:10,500|nullable',
            'type_id' => 'required'
        ]);

        $type_id = intval($request->type_id);

        if ($type_id === ResourceTypesLkp::COMMUNICATION) {
            $this->validate($request, ['sound' => 'required|file|between:10,1000|nullable']);
            $manager = $this->CommunicationResourcesPackageManager;
            $ret_route = "communication_resources.edit";
        } else if ($type_id === ResourceTypesLkp::SIMILAR_GAME) {
            $manager = $this->SimilarityGameResourcesPackageManager;
            $ret_route = "game_resources.edit";
        }
        else{
            throw(new \ValueError("Type not supported"));
        }
        try {
            $resource = $this->ResourceManager->storeResource($request);
            $redirect_id = $resource->resource_parent_id;
            if ($redirect_id == null) {
                $manager->storeResourcePackage($resource, $request['lang']);
                $redirect_id = $resource->id;
            }
            return redirect()->route($ret_route, $redirect_id)
                ->with('flash_message_success', 'The resource package has been successfully created');
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

    public function update(Request $request, int $id)#after submit, (action-route submit button directs here)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
//            'sound' => 'file|between:10,1000|nullable',
            'image' => 'file|between:10,500|nullable'
        ]);
        try {
            $ret = $this->ResourceManager->updateResource($request, $id);

            $redirect_id = $ret['resource_parent_id'] ?: $ret->id;
            $package = $this->resourcesPackageManager->getResourcesPackage($id);
            return redirect()->route('game_resources.edit', $redirect_id)->with('flash_message_success', 'The resource package has been successfully updated');
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
            $this->ResourceManager->destroyResource($id);
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
        try {
            $this->ResourcesPackageManager->approveResourcesPackage($id);
            return redirect()->back()->with('flash_message_success', 'Success! The resource package has been approved');
        } catch (\Exception $e) {
            return redirect()->back()->with('flash_message_failure', 'Warning! The resource package has not been approved');
        }

        //
        #Manager get id of card
        #Manager calls repository
    }


    public function getContentLanguages()
    {
        return $this->ResourceManager->getContentLanguagesForResources();
    }

    public function getResourcesForLanguage(Request $request)
    {
        return $this->ResourceManager->getFirstLevelResourcesWithChildren($request->lang_id);
    }
}
