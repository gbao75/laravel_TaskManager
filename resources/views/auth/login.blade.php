@extends('layouts.app')

@section('title', 'Login - Task Manager')

@section('content')
<div class="auth-shell">
    <section class="card auth-card">
        <h1>Welcome back</h1>
        <p class="subtitle">Sign in to manage your projects and tasks.</p>

        @if ($errors->any())
            <div class="alert alert-danger" style="margin-top: 20px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" autocomplete="current-password">
            </div>

            <button class="btn btn-primary" type="submit">Login</button>
        </form>
    </section>
</div>
@endsection