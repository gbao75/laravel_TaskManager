@extends('layouts.app')

@section('title', 'Create Project - Task Manager')

@section('content')
<main class="container">
    <div class="page-header">
        <div>
            <h1>Create Project</h1>
            <p>Add a new project to your workspace.</p>
        </div>
        <a class="btn btn-secondary" href="{{ route('projects.index') }}">← Back</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="card form-card">
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group full">
                    <label for="name">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Laravel Task Manager">
                </div>

                <div class="form-group full">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="What is this project about?">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Create Project</button>
                <a class="btn btn-secondary" href="{{ route('projects.index') }}">Cancel</a>
            </div>
        </form>
    </section>
</main>
@endsection
