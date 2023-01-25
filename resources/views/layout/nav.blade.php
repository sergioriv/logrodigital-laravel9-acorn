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

    <!-- User Menu Start -->
    <div class="user-container d-flex">
        <a href="#" class="d-flex user position-relative" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <x-avatar-nav :avatar="Auth::user()->avatar" />
            <div class="name">{{ Auth::user()->name }}</div>
        </a>
        <div class="dropdown-menu dropdown-menu-end user-menu wide">
            <div class="row mb-3 ms-0 me-0">
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
                                <i data-acorn-icon="file-text" class="me-2" data-acorn-size="17"></i>
                                <span class="align-middle">{{ __('Docs') }}</span>
                            </a>
                        </li> --}}
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a logro="btn-logout" href="{{ route('logout') }}"
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
            <div class="row mb-1 ms-0 me-0">
                <div class="col-12 p-1 mb-2 pt-2">
                    <div class="text-extra-small text-primary">{{ __('DOCS') }}</div>
                </div>
                <div class="ps-1 pe-1">
                    <ul class="list-unstyled">
                        <li>
                            <a href="https://www.youtube.com/channel/UC-eq0v9pdpjWCrOJ8SFgIkg" target="_blank">
                                <i data-acorn-icon="youtube" class="me-2" data-acorn-size="17"></i>
                                <span class="align-middle">YouTube</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- User Menu End -->

    <!-- Menu Start -->
    <div class="menu-container flex-grow-1 mt-0">
        <ul id="menu" class="menu">

            <li>
                <a href="/dashboard" data-href="/dashboards">
                    <i data-acorn-icon="home" class="icon" data-acorn-size="18"></i>
                    <span class="label">{{ __('Dashboard') }}</span>
                </a>
            </li>
            @can('students.index')
            <li>
                <a href="#students" data-href="/students">
                    <i class="icon icon-18 bi-people"></i>
                    <span class="label">{{ __("Students") }}</span>
                </a>
                <ul id="students">
                    <li>
                        <a href="{{ route("students.no_enrolled") }}">
                            <span class="label text-capitalize">{{ __("no-enrolled") }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route("students.enrolled") }}">
                            <span class="label text-capitalize">{{ __("enrolled") }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route("students.withdraw") }}">
                            <span class="label text-capitalize">{{ __("withdrawn") }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan
            @can('headquarters.index')
            <li>
                <a href="{{ route('headquarters.index') }}" data-href="/headquarters">
                    <i data-acorn-icon="building-large" class="icon" data-acorn-size="18"></i>
                    <span class="label">{{ __('Headquarters') }}</span>
                </a>
            </li>
            @endcan
            @can('subjects.index')
            <li>
                <a href="{{ route('subject.index') }}" data-href="/areas_subjects">
                    <i class="icon icon-18 bi-journals"></i>
                    <span class="label">{{ __('Areas & Subjects') }}</span>
                </a>
            </li>
            @endcan
            @can('groups.index')
            <li>
                <a href="{{ route('group.index') }}" data-href="/groups">
                    <i class="icon icon-18 bi-bookmarks"></i>
                    <span class="label">{{ __('Groups') }}</span>
                </a>
            </li>
            @endcan
            @can('studyTime.index')
            <li>
                <a href="{{ route('studyTime.index') }}" data-href="/study_times">
                    <i data-acorn-icon="clock" class="icon" data-acorn-size="18"></i>
                    <span class="label">{{ __('Study times') }}</span>
                </a>
            </li>
            @endcan
            @can('studyYear.index')
            <li>
                <a href="{{ route('studyYear.index') }}" data-href="/study_years">
                    <i data-acorn-icon="calendar" class="icon" data-acorn-size="18"></i>
                    <span class="label">{{ __('Study years') }}</span>
                </a>
            </li>
            @endcan
            @can('schoolYear.select')
            <li>
                <a href="{{ route('schoolYear.index') }}" data-href="/school_years">
                    <i class="icon icon-18 bi-clock-history"></i>
                    <span class="label">{{ __('School years') }}</span>
                </a>
            </li>
            @endcan
            @can('myinstitution')
            <li>
                <a href="{{ route('myinstitution') }}" data-href="/myinstitution">
                    <i data-acorn-icon="home-garage" class="icon" data-acorn-size="18"></i>
                    <span class="label">{{ __('My Institution') }}</span>
                </a>
            </li>
            @endcan

            @hasrole('TEACHER')
            <li>
                <a href="{{ route('teacher.my.subjects') }}" data-href="/myinstitution">
                    <i class="icon icon-18 bi-journals"></i>
                    <span class="label">{{ __('Subjects') }}</span>
                </a>
            </li>
            @endhasrole


            <div class="separator-light"></div>


            <!-- User Nav Start -->
            <li>
                <a href="{{ route('user.profile.edit') }}" data-href="/profile">
                    <i data-acorn-icon="user" class="icon" data-acorn-size="18"></i>
                    <span class="label">{{ __('Profile') }}</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a logro="btn-logout" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <i data-acorn-icon="logout" class="icon" data-acorn-size="18"></i>
                        <span class="label">{{ __('Log Out') }}</span>
                    </a>
                </form>
            </li>
            <!-- User Nav End -->


            @can('support.access')
            <div class="separator-light"></div>


            <!-- Support Nav Start -->

            <li>
                <a href="{{ route('support.number_students') }}" data-href="/number_students">
                    <i class="bi-gear icon icon-18"></i>
                    <span class="label">{{ __('Students Number') }}</span>
                </a>
            </li>
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
