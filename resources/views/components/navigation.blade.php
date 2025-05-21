<nav class="navbar navbar-expand-lg menu_one menu_purple sticky-nav">
    <div class="container">
        <a class="navbar-brand header_logo" href="{{ route('pages.home') }}">
            <img class="first_logo sticky_logo w-50" src="{{ asset('build/img/logo.png') }}" srcset="{{ asset('build/img/logo-2x.png') }} 2x" alt="logo">
            <img class="white_logo main_logo w-50" src="{{ asset('build/img/logo-w.png') }}" srcset="{{ asset('build/img/logo-w2x.png') }} 2x" alt="logo">
        </a>
        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                    <span class="menu_toggle">
                        <span class="hamburger">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                        <span class="hamburger-cross">
                            <span></span>
                            <span></span>
                        </span>
                    </span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav menu ml-auto">
                <li class="nav-item active">
                    <a href="{{ route('pages.home') }}" class="nav-link">Accueil</a>
                </li>
                <li class="nav-item dropdown submenu">
                    <a href="#" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Domaines</a>
                    <i class="arrow_carrot-down_alt2 mobile_dropdown_icon" aria-hidden="true" data-toggle="dropdown"></i>
                    <ul class="dropdown-menu sub">
                        @foreach($domaines as $domaine)
                            <li class="nav-item {{ $loop->first ? 'active' : '' }}">
                                <img src="{{ Storage::url($domaine->icone) }}" alt="" width="30" class="pr-2">
                                <a class="nav-link" href="{{ route('domaine.show', $domaine) }}">{{ $domaine->titre }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                {{--<li class="nav-item dropdown submenu">
                    <a class="nav-link" href="forums.html">
                        Forum
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Blog
                    </a>
                </li>--}}
            </ul>
            <div class="right-nav">
                {{--<a class="nav_btn" href="#">S'inscrire</a>--}}
                <div class="px-2 js-darkmode-btn" title="Toggle dark mode">
                    <label for="something" class="tab-btn tab-btns">
                        <ion-icon name="moon"></ion-icon>
                    </label>
                    <label for="something" class="tab-btn">
                        <ion-icon name="sunny"></ion-icon>
                    </label>
                    <label class=" ball" for="something"></label>
                    <input type="checkbox" name="something" id="something" class="dark_mode_switcher">
                </div>
            </div>
        </div>
    </div>
</nav>
