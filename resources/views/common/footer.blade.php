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
                    <h3>{{ __('messages.about') }}</h3>
                    <ul>
                        <li><a href="https://www.scify.gr/site/en/">{!! __('messages.team') !!}</a></li>
                        <li><a href="{{ route('content-guidelines')}}">{!! __('messages.content_guidelines') !!}</a>
                        </li>
                        <li><a href="{{ __('messages.terms-of-service-link') }}"
                               target="_blank">{!! __('messages.terms-of-use') !!}</a></li>
                        <li><a href="{{ __('messages.privacy-policy-link') }}"
                               target="_blank">{!! __('messages.privacy-policy') !!}</a></li>
                        <li><a href="{{ __('messages.cookies-policy-link') }}"
                               target="_blank">{!! __('messages.cookies-policy') !!}</a></li>
                        <li>
                            <a href="javascript:void(0);" onclick="toggleCookieBanner()"
                               onkeyup="if (event.key === 'Enter') toggleCookieBanner()"
                               role="button" aria-label="{{ __('cookies_consent::messages.cookies_settings') }}">
                                {{ __('cookies_consent::messages.cookies_settings') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 item text">
                    <h3>{!! __('messages.created_by') !!}</h3>
                    <p style="font-size: small; color:whitesmoke!important"><a
                                href="https://scify.org/en/who-we-are/our-mission/"
                                target="_blank">{!! __('messages.footer-scify') !!}</a>
                    </p>
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
                    <p style="font-size: small; color:white!important">
                        {{ __('messages.funding-footer') }}
                    </p>
                </div>
            </div>
        </div>
    </footer>
</div>
