<div class="footer-dark py-4">
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-3 item">
                    <h3>{!! __('messages.services') !!}</h3>
                    <ul>
                        <li>
                            <a href="{{ route('communication_resources.index') }}">{!! __('messages.communication_cards') !!}</a>
                        </li>
                        <li><a href="{{ route('game_resources.index') }}">{!! __('messages.game_cards') !!}</a></li>
                        <li><a href="{{ config('app.url') }}">{!! __('messages.download') !!} Talk & Play</a></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-md-3 item">
                    <h3>About</h3>
                    <ul>
                        <li><a href="https://www.scify.gr/site/en/">{!! __('messages.team') !!}</a></li>
                        <li><a href="{{ route('content-guidelines')}}">{!! __('messages.content_guidelines') !!}</a>
                        </li>
                        <li><a href="{{route('privacy-policy')}}"
                               target="_blank"> {!! __('messages.privacy-policy') !!}</a></li>
                        <li><a href="{{route('terms-of-use')}}" target="_blank"> {!! __('messages.terms-of-use') !!}</a>
                        </li>
                        <li><a href="https://www.scify.gr/site/en/contact"
                               target="_blank">{!! __('messages.contact_us') !!}</a></li>

                    </ul>
                </div>
                <div class="col-md-6 item text">
                    <h3>Created by</h3>
                    <p><a href="https://www.scify.gr/site/en/who-we-are/scify" target="_blank"
                          rel="noopener noreferrer">SciFY</a> is a not-for-profit organization, that develops
                        cutting-edge information technology systems
                        and freely offers them to all, including the design, the implementation details, and the support
                        needed, in order to solve real-life problems.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <div class="copyright">
                        <p class="m-0">Created by <a href="https://www.scify.gr/site/en/">SciFY</a> @ {{ now()->year }}
                        </p>
                        <p>version <b>{{ config('app.version') }}</b></p>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6" style="margin-left: auto; margin-right: 0;">
                    <div>
                        <img alt="EU Logo" title="" src="{{asset("img/eu_logo.jpg")}}"
                             style="width:70px;height:50px;float:right;display:block;margin-right:100px">
                        <img alt="Shapes Logo" title="" src="{{asset("img/shapes_logo.png")}}"
                             style="width:70px;height:50px; float: right; display: block; background: white; margin-right:10px;">
                    </div>
                    <p style="font-size: small; color:white!important">This project has received funding from the
                        European Union's Horizon 2020 research and innovation programme under grant agreement No.
                        857159.</p>
                </div>
            </div>
        </div>
    </footer>
</div>
