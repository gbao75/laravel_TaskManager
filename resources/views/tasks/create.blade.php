@extends('layouts.app')

@section('title', 'Create Task - Task Manager')

@section('content')
<main class="container">
    <div class="page-header">
        <div>
            <h1>Create Task</h1>
            <p>Create a task and assign it to one of your projects.</p>
        </div>
        <a class="btn btn-secondary" href="{{ route('tasks.index') }}">← Back</a>
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
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="project_id">Project</label>
                    <select id="project_id" name="project_id">
                        <option value="">Select Project</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="pending" @selected(old('status', 'pending') === 'pending')>Pending</option>
                        <option value="in_progress" @selected(old('status') === 'in_progress')>In Progress</option>
                        <option value="completed" @selected(old('status') === 'completed')>Completed</option>
                    </select>
                </div>

                <div class="form-group full">
                    <label for="title">Title</label>
                    <input id="title" type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Finish search and filter feature">
                </div>

                <div class="form-group full">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Task details...">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="deadline">Deadline</label>
                    <input id="deadline" type="date" name="deadline" value="{{ old('deadline') }}">
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Create Task</button>
                <a class="btn btn-secondary" href="{{ route('tasks.index') }}">Cancel</a>
            </div>
        </form>
    </section>
</main>
@endsection