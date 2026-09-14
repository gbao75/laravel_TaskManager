@extends('layouts.app')

@section('title', 'Dashboard - Task Manager')

@section('content')
<main class="container">
    <div class="page-header">
        <div>
            <h1>Dashboard</h1>
            <p>Welcome back, {{ auth()->user()->name }}. Continue managing your work.</p>
        </div>
    </div>

    <section class="dashboard-grid">
        <article class="card dashboard-card">
            <span class="small-muted">PROJECTS</span>
            <div class="number">{{ auth()->user()->projects()->count() }}</div>
            <p>Create, edit and organize the projects that belong to your account.</p>
            <a class="btn btn-primary" href="{{ route('projects.index') }}">View Projects</a>
        </article>

        <article class="card dashboard-card">
            <span class="small-muted">TASKS</span>
            <div class="number">{{ auth()->user()->tasks()->count() }}</div>
            <p>Search, filter, sort and update task status without reloading the page.</p>
            <a class="btn btn-primary" href="{{ route('tasks.index') }}">View Tasks</a>
        </article>
    </section>
</main>
@endsection
