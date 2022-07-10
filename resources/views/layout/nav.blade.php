<div class="nav-content d-flex">
    <!-- Logo Start -->
    <div class="logo position-relative">
        <a href="/">
            <!-- Logo can be added directly -->
            <!-- <img src="/img/logo/logo-white.svg" alt="logo" /> -->
            {{-- <x-application-logo class="w-200 h-200 fill-current text-white" /> --}}
            <div class="img img-logro"></div>
            <!-- Or added via css to provide different ones for different color themes -->
            {{-- <div class="img"></div> --}}
        </a>
    </div>
    <!-- Logo End -->

    <!-- Language Switch Start
    <div class="language-switch-container">
        <button class="btn btn-empty language-button dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">EN</button>
        <div class="dropdown-menu">
            <a href="#" class="dropdown-item">DE</a>
            <a href="#" class="dropdown-item active">EN</a>
            <a href="#" class="dropdown-item">ES</a>
        </div>
    </div>
    <!-- Language Switch End -->

    <!-- User Menu Start -->
    <div class="user-container d-flex">
        <a href="#" class="d-flex user position-relative" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <x-avatar-nav :avatar="Auth::user()->avatar" />
            <div class="name">{{ Auth::user()->name }}</div>
        </a>
        <div class="dropdown-menu dropdown-menu-end user-menu wide">
            <div class="row mb-1 ms-0 me-0">
                <div class="col-12 p-1 mb-3 pt-3">
                    <div class="text-extra-small text-primary">{{ __('ACCOUNT') }}</div>
                </div>
                <div class="ps-1 pe-1">
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{ route('user.profile.edit') }}">
                                <i data-acorn-icon="user" class="me-2" data-acorn-size="17"></i>
                                <span class="align-middle">{{ __('Profile') }}</span>
                            </a>
                        </li>
                        {{-- <li>
                            <a href="#">
                                <i data-acorn-icon="help" class="me-2" data-acorn-size="17"></i>
                                <span class="align-middle">{{ __('Help') }}</span>
                            </a>
                        </li> --}}
                        {{-- <li>
                            <a href="#">
                                <i data-acorn-icon="file-text" class="me-2" data-acorn-size="17"></i>
                                <span class="align-middle">{{ __('Docs') }}</span>
                            </a>
                        </li> --}}
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    <i data-acorn-icon="logout" class="me-2" data-acorn-size="17"></i>
                                    <span class="align-middle">{{ __('Log Out') }}</span>
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- User Menu End -->

    <!-- Icons Menu Start
    <ul class="list-unstyled list-inline text-center menu-icons">
        <li class="list-inline-item">
            <a href="#" data-bs-toggle="modal" data-bs-target="#searchPagesModal">
                <i data-acorn-icon="search" data-acorn-size="18"></i>
            </a>
        </li>
        <li class="list-inline-item">
            <a href="#" id="pinButton" class="pin-button">
                <i data-acorn-icon="lock-on" class="unpin" data-acorn-size="18"></i>
                <i data-acorn-icon="lock-off" class="pin" data-acorn-size="18"></i>
            </a>
        </li>
        <li class="list-inline-item">
            <a href="#" id="colorButton">
                <i data-acorn-icon="light-on" class="light" data-acorn-size="18"></i>
                <i data-acorn-icon="light-off" class="dark" data-acorn-size="18"></i>
            </a>
        </li>
        <li class="list-inline-item">
            <a href="#" data-bs-toggle="dropdown" data-bs-target="#notifications" aria-haspopup="true" aria-expanded="false" class="notification-button">
                <div class="position-relative d-inline-flex">
                    <i data-acorn-icon="bell" data-acorn-size="18"></i>
                    <span class="position-absolute notification-dot rounded-xl"></span>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end wide notification-dropdown scroll-out" id="notifications">
                <div class="scroll">
                    <ul class="list-unstyled border-last-none">
                        <li class="mb-3 pb-3 border-bottom border-separator-light d-flex">
                            <img src="/img/profile/profile-1.webp" class="me-3 sw-4 sh-4 rounded-xl align-self-center" alt="..." />
                            <div class="align-self-center">
                                <a href="#">Joisse Kaycee just sent a new comment!</a>
                            </div>
                        </li>
                        <li class="mb-3 pb-3 border-bottom border-separator-light d-flex">
                            <img src="/img/profile/profile-2.webp" class="me-3 sw-4 sh-4 rounded-xl align-self-center" alt="..." />
                            <div class="align-self-center">
                                <a href="#">New order received! It is total $147,20.</a>
                            </div>
                        </li>
                        <li class="mb-3 pb-3 border-bottom border-separator-light d-flex">
                            <img src="/img/profile/profile-3.webp" class="me-3 sw-4 sh-4 rounded-xl align-self-center" alt="..." />
                            <div class="align-self-center">
                                <a href="#">3 items just added to wish list by a user!</a>
                            </div>
                        </li>
                        <li class="pb-3 border-bottom border-separator-light d-flex">
                            <img src="/img/profile/profile-6.webp" class="me-3 sw-4 sh-4 rounded-xl align-self-center" alt="..." />
                            <div class="align-self-center">
                                <a href="#">Kirby Peters just sent a new message!</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </li>
    </ul>
    <!-- Icons Menu End -->

    <!-- Menu Start -->
    <div class="menu-container flex-grow-1">
        <ul id="menu" class="menu">

            @can('support.users')
            <li>
                <a href="/dashboard" data-href="/dashboards">
                    <i data-acorn-icon="home" class="icon" data-acorn-size="18"></i>
                    <span class="label">{{ __('Dashboard') }}</span>
                </a>
            </li>
            <li>
                <a href="#students" data-href="/students">
                    <i class="icon icon-18 bi-people"></i>
                    <span class="label">{{ __("Students") }}</span>
                </a>
                <ul id="students">
                    <li>
                        <a href="{{ route("students.preregistration") }}">
                            <span class="label text-capitalize">{{ __("pre-registration") }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route("students.registration") }}">
                            <span class="label text-capitalize">{{ __("registration") }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="label text-capitalize">{{ __("pre-enrolled") }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="label text-capitalize">{{ __("enrolled") }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('headquarters.index') }}" data-href="/headquarters">
                    <i data-acorn-icon="building-large" class="icon" data-acorn-size="18"></i>
                    <span class="label">{{ __('Headquarters') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('subject.index') }}" data-href="/areas_subjects">
                    <i class="icon icon-18 bi-journals"></i>
                    <span class="label">{{ __('Areas & Subjects') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.index') }}" data-href="/teachers">
                    <i data-acorn-icon="question-hexagon" class="icon" data-acorn-size="18"></i>
                    <span class="label">{{ __('Teachers') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('group.index') }}" data-href="/groups">
                    <i class="icon icon-18 bi-bookmarks"></i>
                    <span class="label">{{ __('Groups') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('studyTime.index') }}" data-href="/study_times">
                    <i data-acorn-icon="clock" class="icon" data-acorn-size="18"></i>
                    <span class="label">{{ __('Study times') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('studyYear.index') }}" data-href="/study_years">
                    <i data-acorn-icon="calendar" class="icon" data-acorn-size="18"></i>
                    <span class="label">{{ __('Study years') }}</span>
                </a>
            </li>


            <div class="separator-light"></div>
            @endcan


            <!-- User Nav Start -->
            @can('support.roles')
            <li>
                <a href="{{ route('user.profile.edit') }}" data-href="/profile">
                    <i data-acorn-icon="user" class="icon" data-acorn-size="18"></i>
                    <span class="label">{{ __('Profile') }}</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <i data-acorn-icon="logout" class="icon" data-acorn-size="18"></i>
                        <span class="label">{{ __('Log Out') }}</span>
                    </a>
                </form>
            </li>
            @endcan
            <!-- User Nav End -->


            @can('support')
            <div class="separator-light"></div>


            <!-- Support Nav Start -->

            <li>
                <a href="{{ route('support.users.index') }}" data-href="/users">
                    <i class="bi-people-fill icon icon-18"></i>
                    <span class="label">{{ __('Users') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('support.roles.index') }}" data-href="/roles">
                    <i class="bi-person-badge icon icon-18"></i>
                    <span class="label">{{ __('Roles') }}</span>
                </a>
            </li>
            <!-- Support Nav End -->
            @endcan
        </ul>
    </div>
    <!-- Menu End -->

    <!-- Mobile Buttons Start -->
    <div class="mobile-buttons-container">
        <!-- Scrollspy Mobile Button Start -->
        <a href="#" id="scrollSpyButton" class="spy-button" data-bs-toggle="dropdown">
            <i data-acorn-icon="menu-dropdown"></i>
        </a>
        <!-- Scrollspy Mobile Button End -->

        <!-- Scrollspy Mobile Dropdown Start -->
        <div class="dropdown-menu dropdown-menu-end" id="scrollSpyDropdown"></div>
        <!-- Scrollspy Mobile Dropdown End -->

        <!-- Menu Button Start -->
        <a href="#" id="mobileMenuButton" class="menu-button">
            <i data-acorn-icon="menu"></i>
        </a>
        <!-- Menu Button End -->
    </div>
    <!-- Mobile Buttons End -->
</div>
<div class="nav-shadow"></div>
