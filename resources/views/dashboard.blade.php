<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Task Manager</title>
</head>

<body>

    <h1>Dashboard</h1>

    <p>
        Welcome, {{ Auth::user()->name }}
    </p>

    <p>
        Email: {{ Auth::user()->email }}
    </p>

    <a href="{{ route('projects.index') }}">
    My Projects 
    </a>

    <br>

    <a href="{{ route('tasks.index') }}">
        Tasks
    </a>

    <form action="{{ route('logout') }}" method="POST">
        @csrf

        <button type="submit">
            Logout
        </button>
    </form>

</body>
</html>