<?php

namespace App\Http\Controllers;

use App\Models\DesktopAppAnnouncementTranslation;
use App\Repository\ContentLanguageLkpRepository;
use App\Repository\DesktopAppAnnouncementRepository;
use App\Repository\DesktopAppAnnouncementTranslationRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DesktopAppAnnouncementController extends Controller {

    protected DesktopAppAnnouncementRepository $desktopAppAnnouncementRepository;
    protected DesktopAppAnnouncementTranslationRepository $desktopAppAnnouncementTranslationRepository;
    protected ContentLanguageLkpRepository $contentLanguageLkpRepository;

    /**
     * @param DesktopAppAnnouncementRepository $desktopAppAnnouncementRepository
     * @param DesktopAppAnnouncementTranslationRepository $desktopAppAnnouncementTranslationRepository
     * @param ContentLanguageLkpRepository $contentLanguageLkpRepository
     */
    public function __construct(DesktopAppAnnouncementRepository            $desktopAppAnnouncementRepository,
                                DesktopAppAnnouncementTranslationRepository $desktopAppAnnouncementTranslationRepository,
                                ContentLanguageLkpRepository                $contentLanguageLkpRepository) {
        $this->desktopAppAnnouncementRepository = $desktopAppAnnouncementRepository;
        $this->desktopAppAnnouncementTranslationRepository = $desktopAppAnnouncementTranslationRepository;
        $this->contentLanguageLkpRepository = $contentLanguageLkpRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index() {
        return view('admin.desktop-app-announcements-management', [
            'announcements' => $this->desktopAppAnnouncementRepository->all($columns = array('*'), $orderColumn = null, $order = null, ['translations', 'translations.language'])->all(),
            'languages' => $this->contentLanguageLkpRepository->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request) {
        $this->validate($request, [
            'announcement_default_title' => 'required|string',
            'announcement_severity' => 'required|integer|digits_between:1,5',
            'announcement_type' => 'required|string',
            'announcement_titles.*' => 'required|string'
        ]);
        $announcement = $this->desktopAppAnnouncementRepository->create([
            'default_title' => $request->announcement_default_title,
            'severity' => $request->announcement_severity,
            'type' => $request->announcement_type
        ]);

        foreach ($request->lang_ids as $index => $lang_id) {
            $this->desktopAppAnnouncementTranslationRepository->create([
                'announcement_id' => $announcement->id,
                'lang_id' => $lang_id,
                'title' => $request->announcement_titles[$index],
                'message' => $request->announcement_messages[$index],
                'link' => $request->announcement_links[$index],
            ]);
        }
        session()->flash('flash_message_success', "Announcement created");
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'announcement_default_title' => 'required|string',
            'announcement_severity' => 'required|integer|digits_between:1,5',
            'announcement_type' => 'required|string',
            'announcement_titles.*' => 'required|string'
        ]);
        $this->desktopAppAnnouncementRepository->update([
            'default_title' => $request->announcement_default_title,
            'severity' => $request->announcement_severity,
            'type' => $request->announcement_type
        ], $id);
        foreach ($request->lang_ids as $index => $lang_id) {
            $announcementTranslation = DesktopAppAnnouncementTranslation::where(['announcement_id' => $id, 'lang_id' => $lang_id])->first();
            $announcementTranslation->title = $request->announcement_titles[$index];
            $announcementTranslation->message = $request->announcement_messages[$index];
            $announcementTranslation->link = $request->announcement_links[$index];
            $announcementTranslation->save();
        }
        session()->flash('flash_message_success', "Announcement updated");
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id) {
        $this->desktopAppAnnouncementRepository->delete($id);
        session()->flash('flash_message_success', "Announcement deleted");
        return back();
    }
}