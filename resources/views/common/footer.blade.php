<div class="footer-dark py-4">
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-3 item">
                    <h3>{!! __('messages.services') !!}</h3>
                    <ul>
                        <li><a href="{{ route('communication_resources.index') }}">{!! __('messages.communication_cards') !!}</a></li>
                        <li><a href="{{ route('game_resources.index') }}">{!! __('messages.game_cards') !!}</a></li>
                        <li><a href="{{ route('content-guidelines')}}">{!! __('messages.content_guidelines') !!}</a></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-md-3 item">
                    <h3>About</h3>
                    <ul>
                        <li><a href="https://www.scify.gr/site/en/">{!! __('messages.team') !!}</a></li>
                        <li><a href="{{ config('app.url') }}">{!! __('messages.download') !!} Talk & Play</a></li>
                        <li><a href="https://www.scify.gr/site/en/contact" target="_blank">{!! __('messages.contact_us') !!}</a></li>
                    </ul>
                </div>
                <div class="col-md-6 item text">
                    <h3>Created by</h3>
                    <p><a href="https://www.scify.gr/site/en/who-we-are/scify" target="_blank">SciFY</a> is a not-for-profit organization, that develops cutting-edge information technology systems
                        and freely offers them to all, including the design, the implementation details, and the support
                        needed, in order to solve real-life problems.</p>
                </div>
            </div>
            <div class="copyright">
                <h3 class="m-0">Created by <a href="https://www.scify.gr/site/en/">SciFY</a> @ {{ now()->year }}</h3>
                <h3>version <b>{{ config('app.version') }}</b></h3>
            </div>
        </div>
    </footer>
</div>
