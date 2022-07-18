<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="modal-header">
    <h4 class="modal-title">{{ $action === 'create' ? 'Create new announcement' : 'Update announcement' }}</h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal"
            aria-label="Close"></button>
</div>

<div class="modal-body">
    <div class="mb-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <label for="{{ $action }}_default_title" class="form-label">Default Title</label>
                    <input type="text" class="form-control" id="{{ $action }}_default_title"
                           name="announcement_default_title"
                           required
                           placeholder="A default title">
                </div>
                <div class="col-2">
                    <label for="{{ $action }}_severity" class="form-label">Severity (from 1 to
                        5)</label>
                    <input type="number" class="form-control" id="{{ $action }}_severity"
                           name="announcement_severity"
                           required
                           placeholder="1">
                </div>
                <div class="col-2">
                    <label for="{{ $action }}_type" class="form-label">Type ('info', 'alert')</label>
                    <input type="text" class="form-control" id="{{ $action }}_type"
                           name="announcement_type"
                           required
                           placeholder="info">
                </div>
                <div class="col-2">
                    <label for="{{ $action }}_type" class="form-label">Version (e.g. 3.2)</label>
                    <input type="text" class="form-control" id="{{ $action }}_version"
                           name="announcement_version"
                           required
                           placeholder="3.2">
                </div>
            </div>
        </div>

    </div>

    @foreach($languages as $language)
        <input type="hidden" value="{{$language->id}}" name="lang_ids[]">
        <div class="container-fluid py-2">
            <div class="row">
                <p><b>{{ $language->name }}</b></p>
            </div>
            <div class="row mb-2">
                <div class="col-6">
                    <label for="{{ $action }}_title_{{ $language->id }}" class="form-label">Title</label>
                    <input type="text" class="form-control" id="{{ $action }}_title_{{ $language->id }}"
                           name="announcement_titles[]"
                           required
                           placeholder="Title in {{ $language->name }}">
                </div>
                <div class="col-6">
                    <label for="{{ $action }}_message_{{ $language->id }}" class="form-label">Message
                        (optional)</label>
                    <input type="text" class="form-control" id="{{ $action }}_message_{{ $language->id }}"
                           name="announcement_messages[]"
                           placeholder="Message in {{ $language->name }}">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="{{ $action }}_link_{{ $language->id }}" class="form-label">Link
                        (optional)</label>
                    <input type="text" class="form-control" id="{{ $action }}_link_{{ $language->id }}"
                           name="announcement_links[]"
                           placeholder="Link in {{ $language->name }}">
                </div>
            </div>
        </div>
        <hr>
    @endforeach
</div>
