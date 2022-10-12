@php use Illuminate\Support\Facades\App; @endphp
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}" tabindex="-1">
            <img
                loading="lazy"
                src="{{ asset('img/tp_logo_small.png') }}" height="50px" alt="Marketplace logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown navbar-item-dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownDownloadApp" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        Talk & Play app <i class="ml-1 fas fa-download"></i>
                    </a>
                    <ul id="navbarDropdownDownloadAppContent" class="dropdown-menu" aria-labelledby="navbarDropdownDownloadApp">
                        <li><a class="dropdown-item"
                               target="_blank"
                               rel="noopener noreferrer"
                               href="https://go.scify.gr/talkandplayexe">{!! __('messages.download_the_app_windows') !!}
                                <i class="ml-2 fab fa-windows"></i></a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item"
                               target="_blank"
                               rel="noopener noreferrer"
                               href="https://go.scify.gr/talkandplaylinux">{!! __('messages.download_the_app_linux') !!}
                                <i class="ml-2 fab fa-linux"></i></a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item"
                               target="_blank"
                               rel="noopener noreferrer"
                               href="https://docs.google.com/document/d/1izUlMZZ5el7P2laoLaYzuGuK46Hhgrmj_WpJnOWEzwY/edit">{!! __('messages.installation_instructions') !!}
                                <i class="ml-2 fas fa-paste"></i></a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="howItWorksDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        {!! __('messages.how_it_works') !!}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="howItWorksDropdown">
                        <li><a class="dropdown-item"
                               href="{{route('how-it-works-marketplace')}}">{!! __('messages.how_it_works') !!} -
                                Marketplace</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item"
                               href="{{route('how-it-works-desktop')}}">{!! __('messages.how_it_works') !!} - Desktop
                                app</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ UrlMatchesMenuItem("communication_resources.index") }}"
                       href="{{route('communication_resources.index')}}">
                        {!! __('messages.communication_cards') !!}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ UrlMatchesMenuItem("game_resources.index") }}"
                       href="{{route('game_resources.index')}}">
                        {!! __('messages.game_cards') !!}
                    </a>
                </li>
                @guest()
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            {!! __('messages.sign_in_register') !!}
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUserActions" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownUserActions">
                            @auth
                                <li>
                                    <a class="dropdown-item" role="button"
                                       data-bs-toggle="modal" data-bs-target="#edit-profile">
                                        {{__('messages.edit-profile')}}
                                    </a>
                                </li>
                            @endauth

                            @can('manage-platform')
                                <li>
                                    <a class="dropdown-item {{ UrlMatchesMenuItem("resources_packages.approve_pending_packages")}}"
                                       href="{{ route('resources_packages.approve_pending_packages') }}">
                                        {!! __('messages.approve_packages') !!}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ UrlMatchesMenuItem("resources.user-reports.get")}}"
                                       href="{{route('resources_packages.reported-packages')}}">
                                        Reported Packages
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ UrlMatchesMenuItem("administration.users.index")}}"
                                       href="{{ route('administration.users.index') }}">
                                        {!! __('messages.user_management') !!}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ UrlMatchesMenuItem("administration.desktop_app_announcements.index")}}"
                                       href="{{ route('administration.desktop_app_announcements.index') }}">
                                        {!! __('messages.desktop_app_announcements') !!}
                                    </a>
                                </li>
                            @endcan
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item"
                                   href="{{route('resources_packages.my_packages')}}">{{__('messages.my_packages')}}</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('auth.logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>

                    </li>
                @endguest
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownLangSelector" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <img
                            loading="lazy"
                            src="{{ asset('img/lang/' . App::getLocale() . '.png') }}"
                            height="20px" alt="Language">
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownLangSelector">
                        <li><a class="dropdown-item" href="{{ route('set-lang-locale', 'en') }}">
                                <img
                                    loading="lazy"
                                    class="mr-2"
                                    src="{{ asset('img/lang/en.png') }}"
                                    height="20px" alt="English">
                                English
                            </a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('set-lang-locale', 'el') }}">
                                <img
                                    loading="lazy"
                                    class="mr-2"
                                    src="{{ asset('img/lang/el.png') }}"
                                    height="20px" alt="Greek">
                                Ελληνικά
                            </a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('set-lang-locale', 'de') }}">
                                <img
                                    loading="lazy"
                                    class="mr-2"
                                    src="{{ asset('img/lang/de.png') }}"
                                    height="20px" alt="German">
                                Deutsch
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
@auth
    <div class="modal fade" id="edit-profile" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body mb-5">
                    <form id="form" enctype="multipart/form-data" role="form" method="POST"
                          action="{{ route('users.update', Auth::user()) }}">
                        @if(true)
                            @method('PUT')
                        @endif
                        {{ csrf_field() }}
                        <div class="form form-new rounded" style="color:blue; text-align: center; font-size:16pt">
                            <p class="form-new__title p-4">
                                {{__('messages.edit-profile-info')}}</p>
                            <hr>
                        </div>
                        <div class="form-new__fields p-5">
                            <div class="col-12">
                                <label for="username" class="form-label">{{__('messages.name')}} <span>*</span></label>
                                <input type="text" class="form-control" id="username" name="name"
                                       value="{{ Auth::user()->name}}">
                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label">Email <span>*</span></label>
                                <input id="email" type="email" class="form-control" name="email"
                                       value="{{ Auth::user()->email}}" required autocomplete="email">
                            </div>
                            <div class="col-12">
                                <label for="password" class="form-label">{{__('messages.password')}}</label>
                                <input id="password-field" type="password" class="form-control"
                                       name="password" placeholder="********">
                                <span toggle="#password-field"
                                      class="fa fa-fw fa-eye field-icon toggle-password me-3"></span>
                            </div>
                        </div>
                        <div class="form-new__submmit-btn d-flex justify-content-end p-5">
                            <div>
                                <p class="mb-5">{{__('messages.continue-confirm')}}</p>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <a class="btn btn-outline-secondary mt-1"
                                           href="{{route('resources_packages.my_packages')}}" style="color: lightgrey">
                                            {{trans("messages.cancel")}}
                                        </a>
                                    </div>
                                    <div class="col-lg-8">
                                        <input id="userEditBtrn" class="btn btn-outline-primary mt-1 ms-4" type="submit"
                                               value="{{__('messages.submit-info')}}">
                                    </div>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endauth
@push('scripts')
    <script>
        $(".toggle-password").click(function () {
            $(this).toggleClass("fa-eye fa-eye-slash");
            let input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

    </script>
@endpush
