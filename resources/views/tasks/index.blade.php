@extends('layouts.app')

@section('title', 'Tasks - Task Manager')

@section('content')
<main class="container">
    <div class="page-header">
        <div>
            <h1>Tasks</h1>
            <p>Search, filter and update your tasks from one place.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('tasks.create') }}">+ Create Task</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <section class="card filter-card">
        <form action="{{ route('tasks.index') }}" method="GET">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="search">Search</label>
                    <input id="search" type="text" name="search" value="{{ request('search') }}" placeholder="Search task title">
                </div>

                <div class="form-group">
                    <label for="filter-status">Status</label>
                    <select id="filter-status" name="status">
                        <option value="">All Status</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        <option value="in_progress" @selected(request('status') === 'in_progress')>In Progress</option>
                        <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="project_id">Project</label>
                    <select id="project_id" name="project_id">
                        <option value="">All Projects</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @selected(request('project_id') == $project->id)>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="sort">Sort</label>
                    <select id="sort" name="sort">
                        <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest</option>
                        <option value="oldest" @selected(request('sort') === 'oldest')>Oldest</option>
                        <option value="deadline_asc" @selected(request('sort') === 'deadline_asc')>Deadline Ascending</option>
                        <option value="deadline_desc" @selected(request('sort') === 'deadline_desc')>Deadline Descending</option>
                    </select>
                </div>
            </div>

            <div class="filter-actions">
                <button class="btn btn-primary btn-sm" type="submit">Apply Filters</button>
                <a class="btn btn-secondary btn-sm" href="{{ route('tasks.index') }}">Reset</a>
            </div>
        </form>
    </section>

    @if ($tasks->isEmpty())
        <section class="card empty-state">
            <h3>No tasks found</h3>
            <p>Try changing the filters or create a new task.</p>
            <a class="btn btn-primary" href="{{ route('tasks.create') }}">Create Task</a>
        </section>
    @else
        <section class="task-grid">
            @foreach ($tasks as $task)
                <article class="card task-card">
                    <div class="task-meta">
                        <span class="chip">{{ $task->project->name }}</span>
                        <span class="chip">Task #{{ $task->id }}</span>
                    </div>

                    <h3>{{ $task->title }}</h3>
                    <p class="description">{{ $task->description ?: 'No description.' }}</p>

                    <div class="task-footer">
                        <div>
                            <div class="small-muted">Deadline</div>
                            <strong>{{ $task->deadline ?? 'No deadline' }}</strong>
                        </div>

                        <div>
                            <label class="small-muted" for="status-{{ $task->id }}">Status</label>
                            <select
                                id="status-{{ $task->id }}"
                                class="task-status status-select status-{{ $task->status }}"
                                data-task-id="{{ $task->id }}"
                                data-url="{{ route('tasks.updateStatus', $task) }}"
                                data-previous-status="{{ $task->status }}"
                            >
                                <option value="pending" @selected($task->status === 'pending')>Pending</option>
                                <option value="in_progress" @selected($task->status === 'in_progress')>In Progress</option>
                                <option value="completed" @selected($task->status === 'completed')>Completed</option>
                            </select>
                            <div class="ajax-message" aria-live="polite"></div>
                        </div>
                    </div>

                    <div class="card-actions" style="margin-top: 16px;">
                        <a class="btn btn-secondary btn-sm" href="{{ route('tasks.edit', $task) }}">Edit</a>
                        <form class="inline-form" action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </section>

        @if ($tasks->hasPages())
            <div class="pagination-wrap">
                <div class="pagination-info">
                    Showing {{ $tasks->firstItem() }}–{{ $tasks->lastItem() }} of {{ $tasks->total() }} tasks
                </div>

                <nav class="pagination" aria-label="Task pagination">
                    @if ($tasks->onFirstPage())
                        <span class="page-link disabled">←</span>
                    @else
                        <a class="page-link" href="{{ $tasks->previousPageUrl() }}" rel="prev">←</a>
                    @endif

                    @foreach ($tasks->getUrlRange(1, $tasks->lastPage()) as $page => $url)
                        @if ($page == $tasks->currentPage())
                            <span class="page-link current">{{ $page }}</span>
                        @else
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($tasks->hasMorePages())
                        <a class="page-link" href="{{ $tasks->nextPageUrl() }}" rel="next">→</a>
                    @else
                        <span class="page-link disabled">→</span>
                    @endif
                </nav>
            </div>
        @endif
    @endif
</main>
@endsection

@push('scripts')
<script>
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content');

    document.querySelectorAll('.task-status').forEach(function (select) {
        select.addEventListener('change', async function () {
            const previousStatus = this.dataset.previousStatus;
            const status = this.value;
            const url = this.dataset.url;
            const message = this.parentElement.querySelector('.ajax-message');

            this.disabled = true;
            message.textContent = 'Saving...';

            try {
                const response = await fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ status }),
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Update failed.');
                }

                this.dataset.previousStatus = data.status;
                this.classList.remove('status-pending', 'status-in_progress', 'status-completed');
                this.classList.add(`status-${data.status}`);
                message.textContent = 'Saved';

                setTimeout(() => {
                    message.textContent = '';
                }, 1600);
            } catch (error) {
                this.value = previousStatus;
                message.textContent = '';
                alert(error.message);
            } finally {
                this.disabled = false;
            }
        });
    });
</script>
@endpush