<?php

namespace App\Http\Controllers\Resource;

use App\BusinessLogicLayer\Resource\ResourceManager;
use App\BusinessLogicLayer\Resource\ResourcesPackageManager;
use App\BusinessLogicLayer\Resource\SimilarityGameResourcesPackageManager;
use App\BusinessLogicLayer\Resource\TimeGameResourcesPackageManager;
use App\BusinessLogicLayer\Resource\ResponseGameResourcesPackageManager;

use App\Http\Controllers\Controller;
use App\Repository\Resource\GameCategoriesLkp;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class GameResourceController extends Controller
{
    protected GameCategoriesLkp $gameCategoriesLkp;
    protected SimilarityGameResourcesPackageManager $similarityGameResourcesPackageManager;
    protected TimeGameResourcesPackageManager $timeGameResourcesPackageManager;
    protected ResponseGameResourcesPackageManager $responseGameResourcesPackageManager;
    protected ResourceManager $resourceManager;
    protected ResourcesPackageManager $resourcesPackageManager;

    public function __construct(GameCategoriesLkp $gameCategoriesLkp,
                                SimilarityGameResourcesPackageManager $similarityGameResourcesPackageManager,
                                TimeGameResourcesPackageManager $timeGameResourcesPackageManager,
                                ResponseGameResourcesPackageManager $responseGameResourcesPackageManager,
                                ResourceManager $resourceManager,
                                ResourcesPackageManager $resourcesPackageManager)
    {
        $this->gameCategoriesLkp = $gameCategoriesLkp;
        $this->resourceManager = $resourceManager;
        $this->resourcesPackageManager = $resourcesPackageManager;

        $this->similarityGameResourcesPackageManager = $similarityGameResourcesPackageManager;
        $this->responseGameResourcesPackageManager = $responseGameResourcesPackageManager;
        $this->timeGameResourcesPackageManager = $timeGameResourcesPackageManager;
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


    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): View
    {
        $this->validate($request, [
            'type_id' => 'required'//TODO check if exists in DB tab
        ]);


        switch (intval($request->type_id)) {
            case GameCategoriesLkp::SIMILAR:
                $createResourcesPackageViewModel = $this->similarityGameResourcesPackageManager->getCreateResourcesPackageViewModel();
                $game = 'SIMILAR';
                break;
            case  GameCategoriesLkp::TIME:
                $createResourcesPackageViewModel = $this->timeGameResourcesPackageManager->getCreateResourcesPackageViewModel();
                $game = 'TIME';
                break;
            case GameCategoriesLkp::RESPONSE:
                $createResourcesPackageViewModel = $this->responseGameResourcesPackageManager->getCreateResourcesPackageViewModel();
                $game = 'RESPONSE';
                break;
            default:
                throw(new ResourceNotFoundException('Game type under development'));
        }
        return view('game_resources.create-edit-game')->with(['viewModel' => $createResourcesPackageViewModel, 'game' => $game]);

    }


    public function edit(int $id)#returns view
    {
        try {
            $package = $this->resourcesPackageManager->getResourcesPackage($id);
            switch ($package->type_id) {
                case GameCategoriesLkp::SIMILAR:
                    $editResourceViewModel = $this->similarityGameResourcesPackageManager->getEditResourceViewModel($id, $package);
                    $game = 'SIMILAR';
                    break;
                case  GameCategoriesLkp::TIME:
                    $editResourceViewModel = $this->timeGameResourcesPackageManager->getEditResourceViewModel($id, $package);
                    $game = 'TIME';
                    break;
                case GameCategoriesLkp::RESPONSE:
                    $editResourceViewModel = $this->responseGameResourcesPackageManager->getEditResourceViewModel($id, $package);
                    $game = 'RESPONSE';
                    break;
                default:
                    throw(new ResourceNotFoundException('Game category not yet implemented'));
            }
            return view('game_resources.create-edit-game')->with(['viewModel' => $editResourceViewModel, 'game'=>$game]);

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

    public function show_packages(int $type_id)
    {
        try {
            switch ($type_id) {
                case GameCategoriesLkp::SIMILAR:
                    $displayPackageVM = $this->similarityGameResourcesPackageManager->getApprovedSimilarityGamePackagesParentResources();
                    break;
                case  GameCategoriesLkp::TIME:
                    $displayPackageVM = $this->timeGameResourcesPackageManager->getApprovedTimeGamePackagesParentResources();
                    break;
                case GameCategoriesLkp::RESPONSE:
                    $displayPackageVM = $this->responseGameResourcesPackageManager->getApprovedResponseGamePackagesParentResources();
                    break;
                default:
                    throw(new ResourceNotFoundException('Game category not yet implemented'));
            }
            return view('game_resources.approved-packages')->with(['viewModel' => $displayPackageVM]);
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

    public function show_package(int $id): View
    {
        $package = $this->resourcesPackageManager->getResourcesPackage($id);

        try {
            switch ($package->type_id) {
                case GameCategoriesLkp::SIMILAR:
                    $createResourceViewModel = $this->similarityGameResourcesPackageManager->getEditResourceViewModel($id, $package);
                    break;
                case  GameCategoriesLkp::TIME:
                    $createResourceViewModel = $this->timeGameResourcesPackageManager->getEditResourceViewModel($id, $package);
                    break;
                case GameCategoriesLkp::RESPONSE:
                    $createResourceViewModel = $this->responseGameResourcesPackageManager->getEditResourceViewModel($id, $package);
                    break;
                default:
                    throw(new ResourceNotFoundException('Game category not yet implemented'));
            }
            return view('game_resources.show-package')->with(['viewModel' => $createResourceViewModel]);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }


}
