@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/resources-packages-index.css') }}">
@endpush
@section('content')
    <section id="intro" class="pt-5 mt-5">
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
    <section id="resources-steps" class="pt-5">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="accordion" id="accordionExample">
{{--                        <div class="accordion-item my-3">--}}
{{--                            <h2 class="accordion-header">--}}
{{--                                <h2 class="accordion-button" type="button" data-bs-toggle="collapse"--}}
{{--                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">--}}
{{--                                    <span class="title-number">1</span> Διάλεξε κατηγορία--}}
{{--                                    <span class="title-number">1</span> {{__('messages.select_category')}}--}}
{{--                                </h2>--}}
{{--                            </h2>--}}
{{--                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">--}}
{{--                                <div class="accordion-body">--}}

{{--                                    Οι κάρτες με τις λέξεις είναι ομαδοποιημένες σε κατηγορίες (πχ. φαγητά). Κάθε--}}
{{--                                    κατηγορία έχει έτοιμες κάρτες λέξεων (πχ. μακαρόνια, τυρί, ketchup). Διάλεξε μια από--}}
{{--                                    τις παρακάτω κατηγορίες και κατέβασε όποιες κάρτες ή και ολόκληρα πακέτα θέλεις.--}}
{{--                                    Μπορείς αργότερα να τα προσαρμόσεις όπως ακριβώς θέλεις.--}}
{{--                                    {{__('messages.select_category_instruction_text')}}--}}

{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="accordion-item my-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <span class="title-number">1</span> Εξερεύνησε τα διαθέσιμα πακέτα επικοινωνίας
{{--                                    <span class="title-number">2</span> {{__('messages.select_package')}}--}}
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo">
                                <div class="accordion-body">

                                    Επίλεξε τη γλώσσα που επιθυμείς, και δες τα διαθέσιμα πακέτα επικοινωνίας. Κάθε πακέτο
                                    επικοινωνίας, συνοδεύεται με ένα όνομα, μία αντιπροσωπευτική εικόνα, και ένα αρχείο ήχου.
                                    Μπορείς να δεις τις κάρτες που περιλαμβάνει πατώντας το κουμπί "Δες τις κάρτες".

{{--                                   {{__('messages.select_package_instruction_text')}}--}}

                                </div>
                            </div>
                        </div>
                        <div class="accordion-item my-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">
                                    <span class="title-number">2</span> Κατέβασε τα πακέτα που σου αρέσουν
{{--                                    {{__('messages.download_selected_packages')}}--}}
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse show"
                                 aria-labelledby="headingThree">
                                <div class="accordion-body">

                                    Κάθε πακέτο έχει ένα ειδικό εικονίδιο <i class="fas fa-file-download"></i>
                                    στη λίστα ενεργειών της (<i class='fa fa-ellipsis-v'></i>).
                                    <br></br>
                                    Πάτα το εικονίδιο για να κατέβουν τα
                                    αρχεία στη συσκευή σου. Έπειτα φόρτωσε τα αρχεία στην εφαρμογή.
{{--                                    {{__('messages.download_selected_packages_instruction_text')}}--}}
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item my-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseFour" aria-expanded="false"
                                        aria-controls="collapseFour">
                                    <span class="title-number">3</span> Κάτι δεν σε καλύπτει; Φτιάξε δικές σου κάρτες
                                    και πακέτα
                                    {{--{{__('messages.create_own_packages')}}--}}
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse show"
                                 aria-labelledby="headingFour">
                                <div class="accordion-body">
                                    <b class="mt-4">Πώς προσθέτω ένα πακέτο;</b><br>
                                    {{--{{__('messages.add_package_instruction_text')}}--}}

                                    Πάτα το κουμπί "Δημιούργησε νέο πακέτο επικοινωνίας", συμπλήρωσε
                                    στη φόρμα που θα εμφανιστεί το όνομα της κάρτας, ανέβασε μια αντιπροσωπευτική εικόνα
                                    και το αρχείο του ήχου που έχεις ηχογραφήσει για να περιγράψεις την εικόνα.
                                    <p class="note mt-3">Σημείωση: Όταν δημιουργείς ένα καινούργιο πακέτο ή κάρτα θα
                                        πρέπει
                                        πρώτα να εγκριθεί απο ένα διαχειριστή για να εμφανιστεί στη συλλογή.</p>
                                    {{--                                    {{__('messages.admin_approval_instruction_text')}}--}}
                                    <br><br>
                                    Αν επιθυμείς μπορείς να αντιγράψεις ένα υπάρχον πακέτο, πατώντας το σήμα <i class='fa fa-ellipsis-v'></i>
                                    και στη συνέχεια επιλέγοντας "clone". Στη συνέχεια, θα μπορείς να προσθέσεις, να αφαιρέσεις ή να τροποποιήσεις
                                    το περιεχόμενο του πακέτου και να το οριστικοποιήσεις ως δικό σου.
                                    <br><br>
                                    <b class="mt-4">Πώς προσθέτω μια κάρτα;</b><br>
                                    {{--{{__('messages.add_card_instruction_text')}}--}}
                                    Στο πακέτο που θέλεις, επιλέγεις το κουμπί "Προσθήκη Νέας Κάρτας" και
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
                                    {{--{{__('messages.download_app')}}--}}
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse show"
                                 aria-labelledby="headingFive">
                                <div class="accordion-body">
                                    Κατέβασε την εφαρμογή για <a href="#">Linux</a> και για <a href="#">Windows</a>.
                                    {{--{{__('messages.download_app_supported_platforms')}}--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="resources-content" class="mt-5">
        <div class="container">
            <div class="row mt-4">
                <div class="col">
                    <a href="{{ route('communication_resources.create') }}"
                        class="btn btn-outline-primary">
                        <i class="fas fa-plus"></i>
                        {{ __('messages.create_new_communication_package') }}
                    </a>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col text-center">
                    <h5 class="hint mb-1">{{ __('messages.see_all_cards_title') }}</h5>
                    <i class="hint hint-arrow fas fa-arrow-down"></i>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col">
                    <resources-packages-with-filters
                        :resources-packages-route="'{{ route('communication_resources.get') }}'"
                        :user='@json($user)'
                        :packages-type="'COMMUNICATION'">
                    </resources-packages-with-filters>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
@endpush
