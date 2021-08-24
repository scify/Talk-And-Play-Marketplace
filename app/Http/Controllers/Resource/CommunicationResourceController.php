<?php

namespace App\Http\Controllers\Resource;

use App\BusinessLogicLayer\Resource\CommunicationResourcesPackageManager;
use App\BusinessLogicLayer\Resource\ResourceManager;
use App\Http\Controllers\Controller;
use App\Repository\Resource\ResourceStatusesLkp;
use App\Repository\Resource\ResourceTypesLkp;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CommunicationResourceController extends Controller {

    protected ResourceManager $resourceManager;
    protected CommunicationResourcesPackageManager $communicationResourcesPackageManager;

    public function __construct(ResourceManager $resourceManager, CommunicationResourcesPackageManager $communicationResourcesPackageManager) {
        $this->resourceManager = $resourceManager;
        $this->communicationResourcesPackageManager = $communicationResourcesPackageManager;
    }


    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View {
        return view('communication_resources.index', ['user' => Auth::user()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View {

//        $createResourceViewModel = $this->communicationResourceManager->getCreateResourceViewModel();
        $createResourcesPackageViewModel = $this->communicationResourcesPackageManager->getCreateResourcesPackageViewModel();
        return view('communication_resources.create-edit')->with(['viewModel' => $createResourcesPackageViewModel]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {

        $this->validate($request, [
            'name' => 'required|string|max:100',
            'sound' => 'required|file|between:10,1000|nullable',
            'image' => 'required|file|between:10,500|nullable'
        ]);

        try {

            $resource = $this->resourceManager->storeResource($request);
            if ($resource->resource_parent_id == null) {
                $this->communicationResourcesPackageManager->storeResourcePackage($resource, $request['lang']);
                return redirect()->route('communication_resources.edit', $resource->id)->with('flash_message_success', 'The resource package has been successfully created');
            }
            return redirect()->route('communication_resources.edit', $resource->resource_parent_id)->with('flash_message_success', 'A new resource card has been successfully added to the package');

        } catch (Exception $e) {
            return redirect()->with('flash_message_failure', 'Failure - resource card has not been added');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)#returns view
    {

        try {
//            $createResourceViewModel = $this->communicationResourceManager->getEditResourceViewModel($id);
            $package = $this->communicationResourcesPackageManager->getResourcesPackage($id);
            $createResourceViewModel = $this->communicationResourcesPackageManager->getEditResourceViewModel($id, $package);
            return view('communication_resources.create-edit')->with(['viewModel' => $createResourceViewModel]);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }

    public function show_packages() {
        try {
//            $createResourceViewModel = $this->communicationResourceManager->getEditResourceViewModel($id);
            $displayPackageVM = $this->communicationResourcesPackageManager->getApprovedCommunicationPackagesParentResources();
            return view('communication_resources.approved-packages')->with(['viewModel' => $displayPackageVM]);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */

    public function show_package(int $id): View {
        try {
//            $createResourceViewModel = $this->communicationResourceManager->getEditResourceViewModel($id);
            $package = $this->communicationResourcesPackageManager->getResourcesPackage($id);
            $createResourceViewModel = $this->communicationResourcesPackageManager->getEditResourceViewModel($id, $package);
            return view('communication_resources.show-package')->with(['viewModel' => $createResourceViewModel]);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }

    public function download_package(int $id): View {
        try {
//            $createResourceViewModel = $this->communicationResourceManager->getEditResourceViewModel($id);
            $package = $this->communicationResourcesPackageManager->getResourcesPackage($id);
            $this->communicationResourcesPackageManager->downloadPackage($id, $package);
            return $this->show_packages();
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }


    public function getCommunicationResourcePackages(Request $request) {
        return $this->communicationResourcesPackageManager->getResourcesPackages(
            $request->lang_id,
            ResourceTypesLkp::COMMUNICATION,
            ResourceStatusesLkp::APPROVED
        );
    }
}
