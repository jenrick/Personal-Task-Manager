<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tasks') · Daymark</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <a class="brand" href="{{ route('tasks.index') }}" aria-label="Daymark home">
                <span class="brand-mark">D</span>
                <span>Daymark</span>
            </a>
            <div class="sidebar-rule"></div>
            <p class="sidebar-label">TASKS</p>
            <nav class="side-nav" aria-label="Task filters">
                <a class="side-link {{ $filter === 'all' ? 'active' : '' }}" href="{{ route('tasks.index') }}">
                    <span class="nav-symbol">◷</span><span>All tasks</span><span class="nav-count">{{ $counts['all'] }}</span>
                </a>
                <a class="side-link {{ $filter === 'pending' ? 'active' : '' }}" href="{{ route('tasks.index', ['status' => 'pending']) }}">
                    <span class="nav-symbol nav-symbol-pending">○</span><span>Pending</span><span class="nav-count">{{ $counts['pending'] }}</span>
                </a>
                <a class="side-link {{ $filter === 'completed' ? 'active' : '' }}" href="{{ route('tasks.index', ['status' => 'completed']) }}">
                    <span class="nav-symbol nav-symbol-done">✓</span><span>Completed</span><span class="nav-count">{{ $counts['completed'] }}</span>
                </a>
            </nav>
            <div class="sidebar-bottom">
                <span class="sidebar-date">{{ now()->format('l, F j') }}</span>
                <span class="sidebar-caption">Personal workspace</span>
            </div>
        </aside>

        <main class="main-content">
            @yield('content')
        </main>
    </div>
</body>
</html>