@extends('layouts.app')

@section('title', 'Projects - Task Manager')

@section('content')
<main class="container">
    <div class="page-header">
        <div>
            <h1>Projects</h1>
            <p>Projects owned by your account.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('projects.create') }}">+ Create Project</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form
        action="{{ route('projects.index') }}"
        method="GET"
        class="filter-card card"
    >
        <div class="filter-grid">
            <div class="form-group">
                <label>Search Project</label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search project name"
                >
            </div>
        </div>

        <div class="filter-actions">
            <button
                type="submit"
                class="btn btn-primary"
            >
                Search
            </button>

            <a
                href="{{ route('projects.index') }}"
                class="btn btn-secondary"
            >
                Reset
            </a>
        </div>
    </form>

    @if ($projects->isEmpty())
        <section class="card empty-state">
            <h3>No projects yet</h3>
            <p>Create your first project to start organizing tasks.</p>
            <a class="btn btn-primary" href="{{ route('projects.create') }}">Create Project</a>
        </section>
    @else
        <section class="project-grid">
            @foreach ($projects as $project)
                <article class="card project-card">
                    <div>
                        <span class="chip">Project #{{ $project->id }}</span>
                        <h3 style="margin-top: 12px;">{{ $project->name }}</h3>
                        <p class="description">{{ $project->description ?: 'No description.' }}</p>
                    </div>

                    <div class="project-footer">
                        <span class="small-muted">{{ $project->tasks_count }} task(s)</span>
                        <div class="card-actions">
                            <a class="btn btn-secondary btn-sm" href="{{ route('projects.edit', $project) }}">Edit</a>
                            <form class="inline-form" action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Delete this project? Its related tasks may also be deleted.');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>
        @if ($projects->hasPages())
        <div class="pagination-wrap">
            <div class="pagination-info">
                Showing {{ $projects->firstItem() }}–{{ $projects->lastItem() }} of {{ $projects->total() }} projects
            </div>

            <nav class="pagination" aria-label="Project pagination">
                @if ($projects->onFirstPage())
                    <span class="page-link disabled">←</span>
                @else
                    <a class="page-link" href="{{ $projects->previousPageUrl() }}" rel="prev">←</a>
                @endif

                @foreach ($projects->getUrlRange(1, $projects->lastPage()) as $page => $url)
                    @if ($page == $projects->currentPage())
                        <span class="page-link current">{{ $page }}</span>
                    @else
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($projects->hasMorePages())
                    <a class="page-link" href="{{ $projects->nextPageUrl() }}" rel="next">→</a>
                @else
                    <span class="page-link disabled">→</span>
                @endif
            </nav>
        </div>
    @endif
    @endif
</main>
@endsection