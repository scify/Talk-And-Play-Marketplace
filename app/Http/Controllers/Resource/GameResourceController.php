<?php

namespace App\Http\Controllers\Resource;

use App\BusinessLogicLayer\Resource\CommunicationResourceManager;
use App\BusinessLogicLayer\Resource\CommunicationResourcesPackageManager;
use App\BusinessLogicLayer\Resource\SimilarityGameResourceManager;
use App\Http\Controllers\Controller;
use App\Models\ContentLanguageLkp;
use App\Repository\Resource\GameCategoriesLkp;
use App\Repository\GameCategoriesLkpRepository;
use App\Models\Resource\Resource;
use App\ViewModels\CreateEditResourceVM;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GameResourceController extends Controller
{
    protected SimilarityGameResourceManager $similarityGameResourceManager;
    protected GameCategoriesLkp $gameCategoriesLkp;
    protected CommunicationResourcesPackageManager $communicationResourcesPackageManager;

    public function __construct(GameCategoriesLkp $gameCategoriesLkp, SimilarityGameResourceManager $similarityGameResourceManager,CommunicationResourcesPackageManager $communicationResourcesPackManager)
    {
        $this->gameCategoriesLkp = $gameCategoriesLkp;
        $this->similarityGameResourceManager = $similarityGameResourceManager;
        $this->communicationResourcesPackageManager = $communicationResourcesPackManager;
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


    public function game_creation(int $game_id): View
    {
        if ($game_id === GameCategoriesLkp::SIMILAR) {
            $createResourcesPackageViewModel = $this->communicationResourcesPackageManager->getCreateResourcesPackageViewModel();
            return view('game_resources.create-edit-similarity-game')->with(['viewModel' => $createResourcesPackageViewModel]);
        }
    }


}
