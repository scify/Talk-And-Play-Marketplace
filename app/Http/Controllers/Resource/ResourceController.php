<?php

namespace App\Http\Controllers\Resource;

use App\BusinessLogicLayer\Resource\ResourceManager;
use App\BusinessLogicLayer\Resource\ResourcesPackageManager;
use App\Http\Controllers\Controller;
use App\Models\Resource\Resource;
use App\ViewModels\CreateEditResourceVM;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ResourceController extends Controller
{

    protected ResourceManager $ResourceManager;
    protected ResourcesPackageManager $ResourcesPackageManager;

    public function __construct(ResourceManager $ResourceManager, ResourcesPackageManager $ResourcesPackageManager) {
        $this->ResourceManager = $ResourceManager;
        $this->ResourcesPackageManager = $ResourcesPackageManager;
    }


    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View {
        return view('resources.index');
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

            $resource = $this->ResourceManager->storeResource($request);
            if($resource->resource_parent_id == null) {
                $this->ResourcesPackageManager->storeResourcePackage($resource, $request['lang']);
                return redirect()->route('resources.edit', $resource->id)->with('flash_message_success', 'The resource package has been successfully created');
            }
            return redirect()->route('resources.edit', $resource->resource_parent_id)->with('flash_message_success', 'A new resource card has been successfully added to the package');

        }
        catch (Exception $e){
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
            'sound' => 'file|between:10,1000|nullable',
            'image' => 'file|between:10,500|nullable'
        ]);
        try {
            $ret = $this->ResourceManager->updateResource($request,$id);
            $redirect_id = $ret['resource_parent_id'] ?: $ret->id;
            return redirect()->route('resources.edit',$redirect_id)->with('flash_message_success', 'The resource package has been successfully updated');
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
            $this->ResourceManager->destroyResource($id);
            return redirect()->back()->with('flash_message_success', 'Success! The resource has been deleted');
        } catch(\Exception $e){
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
        } catch(\Exception $e){
            return redirect()->back()->with('flash_message_failure', 'Warning! The resource package has not been approved');
        }

        //
        #Manager get id of card
        #Manager calls repository
    }



    public function getContentLanguages() {
        return $this->ResourceManager->getContentLanguagesForResources();
    }

    public function getResourcesForLanguage(Request $request) {
        return $this->ResourceManager->getFirstLevelResourcesWithChildren($request->lang_id);
    }
}
