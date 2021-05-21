<?php


namespace App\Http\Controllers;


class CommunicationCardsController extends Controller {

    public function showCommunicationCardsPage() {
        return view('communication_cards.index');
    }

}
