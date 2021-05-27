@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/communication-cards.css') }}">
@endpush
@section('content')
    <section id="communication-cards-intro" class="pt-5 mt-5">
        <div class="container">
            <div class="row mb-3">
                <div class="col text-center">
                    <h1>{!! __('messages.communication_cards') !!}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <p class="mb-1">{!! __('messages.communication_cards_page_intro') !!}</p>
                    <a class="link-info" href="#">{!! __('messages.communication_cards_page_intro_link') !!}</a>
                </div>
            </div>
        </div>
    </section>
    <section id="communication-cards-steps" class="pt-5">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item my-3">
                            <h2 class="accordion-header">
                                <h2 class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <span class="title-number">1</span> Διάλεξε κατηγορία
                                </h2>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                                <div class="accordion-body">
                                    Οι κάρτες με τις λέξεις είναι ομαδοποιημένες σε κατηγορίες (πχ. φαγητά). Κάθε
                                    κατηγορία έχει έτοιμες κάρτες λέξεων (πχ. μακαρόνια, τυρί, ketchup). Διάλεξε μια από
                                    τις παρακάτω κατηγορίες και κατέβασε όποιες κάρτες ή και ολόκληρα πακέτα θέλεις.
                                    Μπορείς αργότερα να τα προσαρμόσεις όπως ακριβώς θέλεις.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item my-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <span class="title-number">2</span> Διάλεξε πακέτο
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo">
                                <div class="accordion-body">
                                    Βρές έτοιμα πακέτα λέξεων και διάλεξε αυτά που σε εξυπηρετούν.

                                    <p class="note mt-3">Σημείωση: Όταν δημιουργείς ένα καινούργιο πακέτο ή κάρτα θα
                                        πρέπει
                                        πρώτα να εγκριθεί απο ένα διαχειριστή για να εμφανιστεί στη συλλογή.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item my-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">
                                    <span class="title-number">3</span> Κατέβασε τα αρχεία που διάλεξες
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse show"
                                 aria-labelledby="headingThree">
                                <div class="accordion-body">
                                    Κάθε κάρτα ή πακέτο έχει ένα ειδικό εικονίδιο <i class="fas fa-file-download"></i>.
                                    Πάτα το εικονίδιο για να κατέβουν τα
                                    αρχεία στη συσκευή σου. Έπειτα φόρτωσε τα αρχεία στην εφαρμογή.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item my-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseFour" aria-expanded="false"
                                        aria-controls="collapseFour">
                                    <span class="title-number">4</span> Κάτι δεν σε καλύπτει; Φτιάξε δικές σου κάρτες
                                    και πακέτα
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse show"
                                 aria-labelledby="headingFour">
                                <div class="accordion-body">
                                    <b>Πώς προσθέτω κατηγορία;</b><br>
                                    Πάτα το κουμπί + (Δημιούργησε νέα κατηγορία) και συμπλήρωσε στη φόρμα που θα
                                    εμφανιστεί το όνομα της κάρτας, ανέβασε μια αντιπροσωπευτική εικόνα και το αρχείο
                                    του ήχου που έχεις ηχογραφήσει για να περιγράψεις την εικόνα.
                                    <br><br>
                                    <b class="mt-4">Πώς προσθέτω ένα πακέτο;</b><br>
                                    Μόλις επιλέξεις την κατηγορία που θέλεις, πάτα το κουμπί "Νέο πακέτο" και συμπλήρωσε
                                    στη φόρμα που θα εμφανιστεί το όνομα της κάρτας, ανέβασε μια αντιπροσωπευτική εικόνα
                                    και το αρχείο του ήχου που έχεις ηχογραφήσει για να περιγράψεις την εικόνα.
                                    <br><br>
                                    <b class="mt-4">Πώς προσθέτω μια κάρτα;</b><br>
                                    Στο πακέτο που θέλεις, επιλέγεις το κουμπί "πρόσθεσε μια καινούργια κάρτα" και
                                    συμπλήρωσε στη φόρμα που θα εμφανιστεί το όνομα της κάρτας, ανέβασε μια
                                    αντιπροσωπευτική εικόνα και το αρχείο του ήχου που έχεις ηχογραφήσει για να
                                    περιγράψεις την εικόνα.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item my-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseFive" aria-expanded="false"
                                        aria-controls="collapseFive">
                                    <span class="title-number">5</span> Κατέβασε την εφαρμογή για να βοηθήσεις τους
                                    ασθενείς.
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse show"
                                 aria-labelledby="headingFive">
                                <div class="accordion-body">
                                    Κατέβασε την εφαρμογή για <a href="#">Linux</a> και για <a href="#">Windows</a>.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="communication-cards-content" class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <h5 class="hint mb-1">Επεξεργάσου τις κάρτες</h5>
                    <i class="hint hint-arrow fas fa-arrow-down"></i>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col">
                    <communication-resources-with-filters>
                    </communication-resources-with-filters>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
@endpush
