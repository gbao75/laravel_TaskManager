<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Task Manager</title>
</head>

<body>

    <h1>Login</h1>

    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <p style="color: red;">
                    {{ $error }}
                </p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('login.submit') }}" method="POST">

        @csrf

        <div>
            <label>Email</label>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
            >
        </div>

        <br>

        <div>
            <label>Password</label>

            <input
                type="password"
                name="password"
            >
        </div>

        <br>

        <button type="submit">
            Login
        </button>

    </form>

</body>
</html>