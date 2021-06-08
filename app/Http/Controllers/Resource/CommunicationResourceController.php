<?php

namespace App\Http\Controllers\Resource;

use App\BusinessLogicLayer\Resource\CommunicationResourceManager;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommunicationResourceController extends Controller
{

    protected CommunicationResourceManager $communicationResourceManager;

    public function __construct(CommunicationResourceManager $communicationResourceManager) {
        $this->communicationResourceManager = $communicationResourceManager;
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
        // TODO create a new ViewModel instance for the page
        return view('communication_resources.create-edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function getContentLanguages() {
        return $this->communicationResourceManager->getContentLanguagesForCommunicationResources();
    }

    public function getCommunicationResourcesForLanguage(Request $request) {
        return $this->communicationResourceManager->getFirstLevelResourcesWithChildren($request->lang_id);
    }
}
