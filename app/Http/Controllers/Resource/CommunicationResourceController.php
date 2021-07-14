<?php

namespace App\Http\Controllers\Resource;

use App\BusinessLogicLayer\Resource\CommunicationResourceManager;
use App\BusinessLogicLayer\Resource\CommunicationResourcesPackManager;
use App\Http\Controllers\Controller;
use App\Models\Resource\Resource;
use App\ViewModels\CreateEditResourceVM;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommunicationResourceController extends Controller
{

    protected CommunicationResourceManager $communicationResourceManager;
    protected CommunicationResourcesPackManager $communicationResourcesPackManager;

    public function __construct(CommunicationResourceManager $communicationResourceManager, CommunicationResourcesPackManager $communicationResourcesPackManager) {
        $this->communicationResourceManager = $communicationResourceManager;
        $this->communicationResourcesPackManager = $communicationResourcesPackManager;
    }


    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View {
        return view('communication_resources.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View {

//        $createResourceViewModel = $this->communicationResourceManager->getCreateResourceViewModel();
        $createResourcesPackViewModel = $this->communicationResourcesPackManager->getCreateResourcesPackViewModel();
        return view('communication_resources.create-edit')->with(['viewModel' => $createResourcesPackViewModel]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string|max:100',
            'sound' => 'required|file|between:10,1000|nullable',
            'image' => 'required|file|between:10,500|nullable'
        ]);

        try {
            $ret = $this->communicationResourceManager->storeCommunicationResource($request);
            if($ret->resource_parent_id) {
                return redirect()->route('communication_resources.edit', $ret->resource_parent_id)->with('flash_message_success', 'The resource package has been successfully created');
            }
            return redirect()->route('communication_resources.edit', $ret->id)->with('flash_message_success', 'A new resource card has been successfully added to the package');

        }
        catch (Exception $e){
            return redirect()->with('flash_message_failure', 'Failure - resource card has not been added');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)#returns view
    {

        try {
//            $createResourceViewModel = $this->communicationResourceManager->getEditResourceViewModel($id);
            $createResourcesPackViewModel = $this->communicationResourcesPackManager->getEditResourcesPackViewModel($id);
            return view('communication_resources.create-edit')->with(['viewModel' => $createResourcesPackViewModel]);
        } catch (ModelNotFoundException $e){
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */

    //TODO Resource has been succesfully updated (/stored) when card has parent
    public function update(Request $request, int $id)#after submit, (action-route submit button directs here)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'sound' => 'file|between:10,1000|nullable',
            'image' => 'file|between:10,500|nullable'
        ]);
        try {
            $ret = $this->communicationResourceManager->updateCommunicationResource($request,$id);
            $redirect_id = $ret['resource_parent_id'] ?: $ret->id;
            return redirect()->route('communication_resources.edit',$redirect_id)->with('flash_message_success', 'The resource package has been successfully updated');
        } catch(\Exception $e){
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
            $this->communicationResourceManager->destroyCommunicationResource($id);
            return redirect()->back()->with('flash_message_success', 'Success! The resource has been deleted');
        } catch(\Exception $e){
            return redirect()->back()->with('flash_message_failure', 'Warning! The resource has not been deleted');
        }

        //
        #Manager get id of card
        #Manager calls repository
    }

    public function getContentLanguages() {
        return $this->communicationResourcesPackManager->getContentLanguagesForCommunicationResources();
    }

    public function getCommunicationResourcesForLanguage(Request $request) {
        return $this->communicationResourcesPackManager->getFirstLevelResourcesWithChildren($request->lang_id);
    }
}
