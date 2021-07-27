<?php

namespace App\Http\Controllers\Resource;

use App\BusinessLogicLayer\Resource\CommunicationResourceManager;
use App\BusinessLogicLayer\Resource\CommunicationResourcesPackageManager;
use App\BusinessLogicLayer\Resource\ResourceManager;
use App\BusinessLogicLayer\Resource\ResourcesPackageManager;
use App\BusinessLogicLayer\Resource\SimilarityGameResourceManager;
use App\BusinessLogicLayer\Resource\SimilarityGameResourcesPackageManager;
use App\Http\Controllers\Controller;
use App\Models\ContentLanguageLkp;
use App\Repository\Resource\GameCategoriesLkp;
use App\Repository\GameCategoriesLkpRepository;
use App\Models\Resource\Resource;
use App\ViewModels\CreateEditResourceVM;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Repository\Resource\ResourceTypesLkp;
use Illuminate\Http\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class GameResourceController extends Controller
{
    protected SimilarityGameResourceManager $similarityGameResourceManager;
    protected GameCategoriesLkp $gameCategoriesLkp;
    protected SimilarityGameResourcesPackageManager $similarityGameResourcesPackageManager;
    protected ResourceManager $resourceManager;
    protected ResourcesPackageManager $resourcesPackageManager;

    public function __construct(GameCategoriesLkp $gameCategoriesLkp,
                                SimilarityGameResourceManager $similarityGameResourceManager,
                                SimilarityGameResourcesPackageManager $similarityGameResourcesPackageManager,
                                ResourceManager $resourceManager,
                                ResourcesPackageManager $resourcesPackageManager)
    {
        $this->gameCategoriesLkp = $gameCategoriesLkp;
        $this->resourceManager = $resourceManager;
        $this->similarityGameResourceManager = $similarityGameResourceManager;
        $this->resourcesPackageManager = $resourcesPackageManager;

        $this->similarityGameResourcesPackageManager = $similarityGameResourcesPackageManager;
    }


    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('game_resources.index')->with(['gameCategoriesLkp' => $this->gameCategoriesLkp]);
    }


    public function create(Request $request): View
    {
        $this->validate($request, [
            'type_id' => 'required'//TODO check if exists in DB tab
        ]);

        if (intval($request->type_id) === GameCategoriesLkp::SIMILAR) {
            $createResourcesPackageViewModel = $this->similarityGameResourcesPackageManager->getCreateResourcesPackageViewModel();
            return view('game_resources.create-edit-similarity-game')->with(['viewModel' => $createResourcesPackageViewModel]);
        }
    }



//
//    public function game_creation(int $game_id): View
//    {
//        if ($game_id === GameCategoriesLkp::SIMILAR) {
//            $createResourcesPackageViewModel = $this->similarityGameResourcesPackageManager->getCreateResourcesPackageViewModel();
//            return view('game_resources.create-edit-similarity-game')->with(['viewModel' => $createResourcesPackageViewModel]);
//        }
//    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param int $type_id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {

        $this->validate($request, [
            'name' => 'required|string|max:100',
            'image' => 'required|file|between:10,500|nullable',
            'type_id' => 'required'
        ]);

        try {

            if (intval($request->type_id) === ResourceTypesLkp::SIMILAR_GAME) {
                $resource = $this->similarityGameResourceManager->storeResource($request);
                if ($resource->resource_parent_id == null) {
                    $this->similarityGameResourcesPackageManager->storeResourcePackage($resource, $request['lang']);
                    return redirect()->route('game_resources.edit', $resource->id)->with('flash_message_success', 'The resource package has been successfully created');
                }
            }
            return redirect()->route('game_resources.edit', $resource->resource_parent_id)->with('flash_message_success', 'A new resource card has been successfully added to the package');

        } catch (Exception $e) {
            return redirect()->with('flash_message_failure', 'Failure - resource card has not been added');
        }

    }


    public function edit(int $id)#returns view
    {
        try {
            $package = $this->resourcesPackageManager->getResourcesPackage($id);
            if ($package->type_id === ResourceTypesLkp::SIMILAR_GAME) {
                $createResourceViewModel = $this->similarityGameResourceManager->getEditResourceViewModel($id, $package);
                return view('game_resources.create-edit-similarity-game')->with(['viewModel' => $createResourceViewModel]);
            } else {
                throw(new ResourceNotFoundException("Game type not yet supported"));
            }

        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */

    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse#after submit, (action-route submit button directs here)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'image' => 'file|between:10,500|nullable'
        ]);
        try {
            $ret = $this->resourceManager->updateResource($request, $id);
            $redirect_id = $ret['resource_parent_id'] ?: $ret->id;
            return redirect()->route('game_resources.edit', $redirect_id)->with('flash_message_success', 'The resource package has been successfully updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('flash_message_failure', 'The resource package has not been updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $this->resourceManager->destroyCommunicationResource($id);
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
            $this->resourcesPackageManager->approveCommunicationResourcesPackage($id);
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
        return $this->resourceManager->getContentLanguagesForCommunicationResources();
    }

    public function getCommunicationResourcesForLanguage(Request $request)
    {
        return $this->resourcesPackageManager->getFirstLevelResourcesWithChildren($request->lang_id);
    }


}
