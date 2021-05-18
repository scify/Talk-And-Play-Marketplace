@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/home.css') }}">
@endpush
@section('content')
    <div id="intro-carousel-row">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col">
                    <div class="main-carousel" id="landing-page-intro-carousel">
                        <div class="carousel-cell">
                            <div class="container">
                                <div class="row align-items-center">
                                    <div class="col-7">
                                        <h1>Talk & Play marketplace</h1>
                                        <p class="description mt-4">
                                            To Talk & Play marketplace δημιουργήθηκε για να διευκολύνει ειδικούς και
                                            συγγενής να
                                            βοηθήσουν τους
                                            ασθενείς που έχουν (την πάθηση). Από εδώ μπορείς να διαχειριστείς ενότητες ή
                                            κάρτες των
                                            κατηγοριών
                                            Κάρτες Επικοινωνίας και Κάρτες παιχνιδιών που υπάρχουν στο <b>Talk & Play
                                                app</b>.
                                        </p>
                                        <p class="mt-5 see-more fw-bold">
                                            Μάθε περισσότερα για το <a href="#">Talk & Play app</a>
                                        </p>
                                    </div>
                                    <div class="col-5">
                                        111111111111111
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-cell">
                            <div class="container">
                                <div class="row align-items-center">
                                    <div class="col-7">
                                        <h1>Talk & Play app</h1>
                                        <p class="description mt-4">
                                            To <b>Talk & Play app</b> δημιουργήθηκε για να διευκολύνει τους ασθενείς που
                                            είναι
                                            τετραπληγικοί
                                            και δεν μπορούν να επικοινωνήσουν εύκολα ,στους γιατρούς και σε όσους τους
                                            προσέχουν, τα
                                            <b>συναισθήματά τους και τις βασικές τους ανάγκες</b> όπως είναι η επιθυμία
                                            για ένα
                                            συγκεκριμένο
                                            φαγητό. Τους δίνεται επίσης η δυνατότητα να περάσουν ευχάριστα τον χρόνο
                                            τους με τις <b>3
                                                κατηγορίες παιχνιδιών</b> που προσφέρουμε (<b>Αντίδρασης</b>, <b>Χρονικής
                                                προτεραιοποίησης</b>, <b>Βρές το
                                                όμοιο</b>).
                                        </p>
                                        <a href="#" class="btn btn-primary">
                                            Κατέβασε την εφαρμογή
                                        </a>
                                        <p class="mt-5 see-more fw-bold">
                                            Μάθε περισσότερα για το <a href="#">Talk & Play app</a>
                                        </p>
                                    </div>
                                    <div class="col-5">
                                        2222222222222
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col text-center">
                    <p class="m-0"><b>Δες τις <a href="#">κατηγορίες καρτών.</a></b></p>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script src="{{ mix('dist/js/home.js') }}"></script>
@endpush
