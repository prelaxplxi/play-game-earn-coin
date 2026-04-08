<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - @yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('admin/css/theme.css') }}">
    <style>
        body { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; padding: 20px; }
        .sidebar a { text-decoration: none; display: block; padding: 10px; border-radius: 5px; }
        .sidebar [aria-expanded="true"] i { transform: rotate(180deg); transition: transform 0.3s ease; }
        .sidebar i { transition: transform 0.3s ease; }
        .content { flex: 1; padding: 30px; }
        #theme-toggle { display: none; }
    </style>
    {{-- Apply saved theme immediately to prevent flash --}}
    <script>
        (function() {
            var theme = localStorage.getItem('admin-theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>
    @stack('styles')
</head>
<body>
    <div class="sidebar">
        <h3>Admin Panel</h3>
        <hr>
        <nav>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a>
            <a href="{{ route('admin.games.index') }}" class="{{ request()->routeIs('admin.games.*') ? 'active' : '' }}">Games</a>
            <a href="{{ route('admin.contests.index') }}" class="{{ request()->routeIs('admin.contests.*') ? 'active' : '' }}">Contests</a>
            
            <a href="#surveySubmenu" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center {{ request()->routeIs('admin.surveys.*') || request()->routeIs('admin.user-surveys.*') ? 'active' : '' }}" aria-expanded="{{ request()->routeIs('admin.surveys.*') || request()->routeIs('admin.user-surveys.*') ? 'true' : 'false' }}">
                <span>Surveys</span>
                <i class="fas fa-chevron-down small"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.surveys.*') || request()->routeIs('admin.user-surveys.*') ? 'show' : '' }} ps-3" id="surveySubmenu">
                <a href="{{ route('admin.surveys.index') }}" class="{{ request()->routeIs('admin.surveys.index') ? 'active' : '' }}">Survey List</a>
                <a href="{{ route('admin.user-surveys.index') }}" class="{{ request()->routeIs('admin.user-surveys.index') ? 'active' : '' }}">User Survey Responses</a>
            </div>

            <a href="{{ route('admin.withdrawals.index') }}" class="{{ request()->routeIs('admin.withdrawals.*') ? 'active' : '' }}">Withdrawals</a>
            <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
        </nav>
    </div>
    <div class="content">
        {{-- Theme Toggle Button --}}
        <div class="d-flex justify-content-end mb-3">
            <button id="theme-toggle" class="theme-toggle-btn" title="Toggle Light/Dark Mode">
                <i class="fas fa-moon"></i>
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
        @yield('content')
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('admin/js/custom.js') }}"></script>
    @stack('scripts')
</body>
</html>
