<div class="container-fluid position-absolute pt-2">
    <div class="row">
        <div class="col-lg-6 col-md-9 col-sm-12 ms-auto">
            @if(session('flash_message_success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><i class="icon fa fa-check"></i> {{ session('flash_message_success') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('flash_message_failure'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="icon fa fa-ban"></i> {{ session('flash_message_failure') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(count($errors) > 0 )
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="icon fa fa-ban"></i> {{ $error }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
