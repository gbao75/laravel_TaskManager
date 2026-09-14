@extends('layouts.app')

@section('title', 'Edit Task - Task Manager')

@section('content')
<main class="container">
    <div class="page-header">
        <div>
            <h1>Edit Task</h1>
            <p>Update task information and assignment.</p>
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
        <form action="{{ route('tasks.update', $task) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label for="project_id">Project</label>
                    <select id="project_id" name="project_id">
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @selected(old('project_id', $task->project_id) == $project->id)>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="pending" @selected(old('status', $task->status) === 'pending')>Pending</option>
                        <option value="in_progress" @selected(old('status', $task->status) === 'in_progress')>In Progress</option>
                        <option value="completed" @selected(old('status', $task->status) === 'completed')>Completed</option>
                    </select>
                </div>

                <div class="form-group full">
                    <label for="title">Title</label>
                    <input id="title" type="text" name="title" value="{{ old('title', $task->title) }}">
                </div>

                <div class="form-group full">
                    <label for="description">Description</label>
                    <textarea id="description" name="description">{{ old('description', $task->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="deadline">Deadline</label>
                    <input id="deadline" type="date" name="deadline" value="{{ old('deadline', $task->deadline) }}">
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Update Task</button>
                <a class="btn btn-secondary" href="{{ route('tasks.index') }}">Cancel</a>
            </div>
        </form>
    </section>
</main>
@endsection