<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Task Manager')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <a class="brand" href="{{ auth()->check() ? route('dashboard') : route('login') }}">
                <span class="brand-mark">T</span>
                <span>Task Manager</span>
            </a>

            @auth
                <div class="nav-links">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ route('projects.index') }}">Projects</a>
                    <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}" href="{{ route('tasks.index') }}">Tasks</a>
                </div>

                <div class="nav-user">
                    <div class="user-meta">
                        <strong>{{ auth()->user()->name }}</strong>
                        <span>{{ auth()->user()->email }}</span>
                    </div>

                    <form class="inline-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-secondary btn-sm" type="submit">Logout</button>
                    </form>
                </div>
            @endauth
        </nav>
    </header>

    @yield('content')

    @stack('scripts')
</body>
</html>