@php
    $user = auth()->user();

    $calendarActive = request()->routeIs('calendar.*');

    // permissions
    $canPlanCreate = (bool) $user->role?->can_create_plans;
    $canPlanApprove = (bool) $user->role?->can_approve_plans;

    $canCourseCreate = (bool) $user->role?->can_create_courses;
    $canCourseApprove = (bool) $user->role?->can_approve_courses;

    $canManageUsers = (bool) $user->role?->can_manage_users;

    // role checks
    $isEmployee = $user->role?->name === 'Karyawan';

    // instructor check
    $isInstructor = $user->instructedCourses()->wherePivot('status', 'active')->exists();

    // menu visibility
    $showPlansMenu = $canPlanCreate || $canPlanApprove;
    $showTorMenu = $canPlanCreate || $canPlanApprove;

    $showCourseMgmtMenu = $canCourseCreate;
    $showUsersMenu = $canManageUsers;

    // active flags
    $dashboardActive = request()->routeIs('dashboard') || request()->routeIs('dashboard.*');

    $plansActive = request()->routeIs('annual-plans.*');
    $myCoursesActive = request()->routeIs('courses_instructor.*') || request()->routeIs('instructor.courses.*');

    $torActive = request()->routeIs('tor-submissions.*');

    $courseMgmtActive = request()->routeIs('courses.*') || request()->routeIs('course-types.*');

    $manageUsersActive =
        request()->routeIs('roles.*') ||
        request()->routeIs('employees.*') ||
        request()->routeIs('job-titles.*') ||
        request()->routeIs('job-categories.*') ||
        request()->routeIs('admin.mot.*');

    // dropdown open state
    $openPlans = $plansActive;
    $openTor = $torActive;
    $openCourseMgmt = $courseMgmtActive;
    $openUsers = $manageUsersActive;

    // only Developer sees "Tambah Roles"
    $isDeveloper = $user->role?->name === 'Developer';

    // ====== UI tokens (selaras) ======
    $navItemBase =
        'flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition ' .
        'hover:bg-white/60 hover:shadow-sm active:scale-[0.99]';

    $navItemInactive = 'text-slate-700';
    $navItemActive = 'bg-white/70 text-[#121293] ring-1 ring-[#121293]/15';

    $groupBtnBase =
        'w-full flex items-center justify-between px-3 py-2 rounded-xl transition ' .
        'hover:bg-white/60 hover:shadow-sm active:scale-[0.99]';

    $navChildBase = 'block px-3 py-2 rounded-xl text-sm transition hover:bg-white/60';

    $navChildInactive = 'text-slate-600';
    $navChildActive = 'bg-white/70 text-[#121293] ring-1 ring-[#121293]/15';
@endphp

<nav class="flex-1 p-3 space-y-1.5">

    {{-- Dashboard --}}
    <a href="{{ route('dashboard') }}"
        class="{{ $navItemBase }} {{ $dashboardActive ? $navItemActive : $navItemInactive }}">
        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
            <path fill="currentColor"
                d="M4 3h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1H4c-.55 0-1-.45-1-1V4c0-.55.45-1 1-1zm10 0h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1h-6c-.55 0-1-.45-1-1V4c0-.55.45-1 1-1zM4 13h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1H4c-.55 0-1-.45-1-1v-6c0-.55.45-1 1-1zm13 0c-.55 0-1 .45-1 1v2h-2c-.55 0-1 .45-1 1s.45 1 1 1h2v2c0 .55.45 1 1 1s1-.45 1-1v-2h2c.55 0 1-.45 1-1s-.45-1-1-1h-2v-2c0-.55-.45-1-1-1z" />
        </svg>
        <span x-show="!collapsed" x-transition.opacity>Dashboard</span>
    </a>

    {{-- Calendar --}}
    <a href="{{ route('calendar.index') }}"
        class="{{ $navItemBase }} {{ $calendarActive ? $navItemActive : $navItemInactive }}">
        <svg class="w-5 h-5 shrink-0" viewBox="0 0 16 16" aria-hidden="true">
            <path fill="currentColor"
                d="M14 1v3h-3V1H5v3H2V1H0v15h16V1h-2zM3 15H1v-2h2v2zm0-3H1v-2h2v2zm0-3H1V7h2v2zm3 6H4v-2h2v2zm0-3H4v-2h2v2zm0-3H4V7h2v2zm3 6H7v-2h2v2zm0-3H7v-2h2v2zm0-3H7V7h2v2zm3 6h-2v-2h2v2zm0-3h-2v-2h2v2zm0-3h-2V7h2v2zm3 6h-2v-2h2v2zm0-3h-2v-2h2v2zm0-3h-2V7h2v2z" />
            <path fill="currentColor" d="M3 0h1v3H3V0zm9 0h1v3h-1V0z" />
        </svg>
        <span x-show="!collapsed" x-transition.opacity>Calendar</span>
    </a>

    {{-- ================= ANNUAL PLANS ================= --}}
    @if ($showPlansMenu)
        <div x-data="{ open: {{ $openPlans ? 'true' : 'false' }} }" class="pt-1">
            <button type="button" @click="open = !open"
                class="{{ $groupBtnBase }} {{ $plansActive ? $navItemActive : $navItemInactive }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor"
                            d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h1V3a1 1 0 0 1 1-1Zm12 8H5v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-9ZM6 6H5a1 1 0 0 0-1 1v1h16V7a1 1 0 0 0-1-1h-1v1a1 1 0 1 1-2 0V6H8v1a1 1 0 1 1-2 0V6Z" />
                    </svg>
                    <span x-show="!collapsed" x-transition.opacity>Annual Plans</span>
                </div>

                <svg x-show="!collapsed" x-transition.opacity class="w-4 h-4 transition-transform duration-300"
                    :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor"
                        d="m12 15l-4.243-4.242l1.415-1.414L12 12.172l2.828-2.828l1.415 1.414L12 15.001Z" />
                </svg>
            </button>

            <div x-show="open && !collapsed" x-collapse.duration.250ms class="mt-2 space-y-1 pl-11 relative">
                <div class="absolute left-5 top-0 bottom-0 w-px bg-slate-200/70"></div>

                <a href="{{ route('annual-plans.index') }}"
                    class="{{ $navChildBase }} {{ request()->routeIs('annual-plans.index') ? $navChildActive : $navChildInactive }}">
                    Daftar Plans
                </a>

                @if ($canPlanCreate && !$canPlanApprove)
                    <a href="{{ route('annual-plans.create') }}"
                        class="{{ $navChildBase }} {{ request()->routeIs('annual-plans.create') ? $navChildActive : $navChildInactive }}">
                        Buat Cover Plan
                    </a>
                @endif

                @if ($canPlanApprove)
                    <a href="{{ route('annual-plans.approvals') }}"
                        class="{{ $navChildBase }} {{ request()->routeIs('annual-plans.approvals') ? $navChildActive : $navChildInactive }}">
                        Kelola Persetujuan
                    </a>
                @endif
            </div>
        </div>
    @endif

    {{-- ================= TOR ================= --}}
    @if ($showTorMenu)
        <div x-data="{ open: {{ $openTor ? 'true' : 'false' }} }" class="pt-1">
            <button type="button" @click="open = !open"
                class="{{ $groupBtnBase }} {{ $torActive ? $navItemActive : $navItemInactive }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor"
                            d="M6 2h9l5 5v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm8 1.5V8h4.5L14 3.5ZM7 12h10v2H7v-2Zm0 4h10v2H7v-2Zm0-8h6v2H7V8Z" />
                    </svg>
                    <span x-show="!collapsed" x-transition.opacity>TOR</span>
                </div>

                <svg x-show="!collapsed" x-transition.opacity class="w-4 h-4 transition-transform duration-300"
                    :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor"
                        d="m12 15l-4.243-4.242l1.415-1.414L12 12.172l2.828-2.828l1.415 1.414L12 15.001Z" />
                </svg>
            </button>

            <div x-show="open && !collapsed" x-collapse.duration.250ms class="mt-2 space-y-1 pl-11 relative">
                <div class="absolute left-5 top-0 bottom-0 w-px bg-slate-200/70"></div>

                @if ($canPlanCreate)
                    <a href="{{ route('tor-submissions.index') }}"
                        class="{{ $navChildBase }} {{ request()->routeIs('tor-submissions.index') ? $navChildActive : $navChildInactive }}">
                        Daftar TOR
                    </a>
                @endif

                @if ($canPlanApprove)
                    <a href="{{ route('tor-submissions.approvals') }}"
                        class="{{ $navChildBase }} {{ request()->routeIs('tor-submissions.approvals') ? $navChildActive : $navChildInactive }}">
                        Kelola Persetujuan
                    </a>
                @endif
            </div>
        </div>
    @endif

    {{-- ================= COURSE MANAGEMENT ================= --}}
    @if ($showCourseMgmtMenu)
        <div x-data="{ open: {{ $openCourseMgmt ? 'true' : 'false' }} }" class="pt-1">
            <button type="button" @click="open = !open"
                class="{{ $groupBtnBase }} {{ $courseMgmtActive ? $navItemActive : $navItemInactive }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor"
                            d="M4 6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v2H4V6Zm0 4h20v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8Zm4 2v2h6v-2H8Z" />
                    </svg>
                    <span x-show="!collapsed" x-transition.opacity>Course Management</span>
                </div>

                <svg x-show="!collapsed" x-transition.opacity class="w-4 h-4 transition-transform duration-300"
                    :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor"
                        d="m12 15l-4.243-4.242l1.415-1.414L12 12.172l2.828-2.828l1.415 1.414L12 15.001Z" />
                </svg>
            </button>

            <div x-show="open && !collapsed" x-collapse.duration.250ms class="mt-2 space-y-1 pl-11 relative">
                <div class="absolute left-5 top-0 bottom-0 w-px bg-slate-200/70"></div>

                <a href="{{ route('courses.index') }}"
                    class="{{ $navChildBase }} {{ request()->routeIs('courses.index') ? $navChildActive : $navChildInactive }}">
                    List Courses
                </a>

                <a href="{{ route('courses.create') }}"
                    class="{{ $navChildBase }} {{ request()->routeIs('courses.create') ? $navChildActive : $navChildInactive }}">
                    Tambah Course
                </a>

                <div class="pt-2 mt-2 border-t border-slate-200/70"></div>

                <a href="{{ route('course-types.index') }}"
                    class="{{ $navChildBase }} {{ request()->routeIs('course-types.index') ? $navChildActive : $navChildInactive }}">
                    Categories
                </a>

                <a href="{{ route('course-types.create') }}"
                    class="{{ $navChildBase }} {{ request()->routeIs('course-types.create') ? $navChildActive : $navChildInactive }}">
                    Tambah Category Course
                </a>
            </div>
        </div>
    @endif

    {{-- ================= MANAGE USERS ================= --}}
    @if ($showUsersMenu)
        <div x-data="{ open: {{ $openUsers ? 'true' : 'false' }} }" class="pt-1">
            <button type="button" @click="open = !open"
                class="{{ $groupBtnBase }} {{ $manageUsersActive ? $navItemActive : $navItemInactive }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor"
                            d="M14 19.5c0-2 1.1-3.8 2.7-4.7c-1.3-.5-2.9-.8-4.7-.8c-4.4 0-8 1.8-8 4v2h10v-.5m5.5-3.5c-1.9 0-3.5 1.6-3.5 3.5s1.6 3.5 3.5 3.5s3.5-1.6 3.5-3.5s-1.6-3.5-3.5-3.5M16 8c0 2.2-1.8 4-4 4s-4-1.8-4-4s1.8-4 4-4s4 1.8 4 4Z" />
                    </svg>
                    <span x-show="!collapsed" x-transition.opacity>Manage Users</span>
                </div>

                <svg x-show="!collapsed" x-transition.opacity
                    class="w-4 h-4 text-slate-600 transition-transform duration-300"
                    :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor"
                        d="m12 15l-4.243-4.242l1.415-1.414L12 12.172l2.828-2.828l1.415 1.414L12 15.001Z" />
                </svg>
            </button>

            <div x-show="open && !collapsed" x-collapse.duration.250ms class="mt-2 space-y-1 pl-11 relative">
                <div class="absolute left-5 top-0 bottom-0 w-px bg-slate-200/70"></div>

                <a href="{{ route('admin.mot.index') }}"
                    class="{{ $navChildBase }} {{ request()->routeIs('admin.mot.*') ? $navChildActive : $navChildInactive }}">
                    Akun Narasumber
                </a>

                <a href="{{ route('employees.index') }}"
                    class="{{ $navChildBase }} {{ request()->routeIs('employees.*') ? $navChildActive : $navChildInactive }}">
                    Akun Karyawan
                </a>

                <a href="{{ route('job-titles.index') }}"
                    class="{{ $navChildBase }} {{ request()->routeIs('job-titles.*') ? $navChildActive : $navChildInactive }}">
                    Tambah Job Title
                </a>

                <a href="{{ route('job-categories.index') }}"
                    class="{{ $navChildBase }} {{ request()->routeIs('job-categories.*') ? $navChildActive : $navChildInactive }}">
                    Tambah Job Categories
                </a>

                @if ($isDeveloper)
                    <a href="{{ route('roles.index') }}"
                        class="{{ $navChildBase }} {{ request()->routeIs('roles.*') ? $navChildActive : $navChildInactive }}">
                        Tambah Roles
                    </a>
                @endif
            </div>
        </div>
    @endif

    {{-- ================= INSTRUCTOR / MY COURSES ================= --}}
    @if ($isInstructor)
        <a href="{{ route('instructor.courses.index') }}"
            class="{{ $navItemBase }} {{ $myCoursesActive ? $navItemActive : $navItemInactive }}">
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="currentColor" d="M20 6H4V4h16v2Zm-2 3H6v2h12V9Zm0 4H6v2h12v-2Zm-3 4H9v2h6v-2Z" />
            </svg>
            <span x-show="!collapsed" x-transition.opacity>My Courses</span>
        </a>
    @endif

    {{-- ================= EMPLOYEE COURSE ================= --}}
    @if ($isEmployee)
        <a href="{{ route('employee.courses.index') }}"
            class="{{ $navItemBase }} {{ request()->routeIs('courses.enroll.*') ? $navItemActive : $navItemInactive }}">
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="currentColor"
                    d="M12 3L1 9l11 6l9-4.91V17h2V9L12 3Zm0 13L4.24 9.69L12 5l7.76 4.69L12 16Z" />
            </svg>
            <span x-show="!collapsed" x-transition.opacity>Enroll Course</span>
        </a>
    @endif

</nav>
