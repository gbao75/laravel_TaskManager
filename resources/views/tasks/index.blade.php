<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tasks</title>
</head>

<body>

    <h1>Tasks</h1>

    @if (session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif
    
    <form
    action="{{ route('tasks.index') }}"
    method="GET"
    >
        <div>
            <label>Search</label>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search task title"
            >
        </div>
        <br>
        <div>
            <label>Status</label>
            <select name="status">
                <option value="">
                    All Status
                </option>

                <option
                    value="pending"
                    @selected(request('status') === 'pending')
                >
                    Pending
                </option>

                <option
                    value="in_progress"
                    @selected(request('status') === 'in_progress')
                >
                    In Progress
                </option>

                <option
                    value="completed"
                    @selected(request('status') === 'completed')
                >
                    Completed
                </option>

            </select>
        </div>

        <br>

        <div>
            <label>Project</label>

            <select name="project_id">

                <option value="">
                    All Projects
                </option>

                @foreach ($projects as $project)

                    <option
                        value="{{ $project->id }}"
                        @selected(
                            request('project_id')
                            == $project->id
                        )
                    >
                        {{ $project->name }}
                    </option>

                @endforeach

            </select>
        </div>

        <br>

        <div>
            <label>Sort</label>
            <select name="sort">
                <option
                    value="newest"
                    @selected(request('sort') === 'newest')
                >
                    Newest
                </option>

                <option
                    value="oldest"
                    @selected(request('sort') === 'oldest')
                >
                    Oldest
                </option>

                <option
                    value="deadline_asc"
                    @selected(request('sort') === 'deadline_asc')
                >
                    Deadline Ascending
                </option>

                <option
                    value="deadline_desc"
                    @selected(request('sort') === 'deadline_desc')
                >
                    Deadline Descending
                </option>
            </select>
        </div>
        <br>
        <button type="submit">
            Apply
        </button>
        <a href="{{ route('tasks.index') }}">
            Reset
        </a>
    </form>

<hr>

    <a href="{{ route('tasks.create') }}">
        Create Task
    </a>

    <hr>

    @forelse ($tasks as $task)

        <div>

            <h3>{{ $task->title }}</h3>
            <p>
                Project:
                {{ $task->project->name }}
            </p>

            <p>
                Description:
                {{ $task->description }}
            </p>

            <div>
                <label>Status:</label>

                <select
                    class="task-status"
                    data-task-id="{{ $task->id }}"
                >

                    <option
                        value="pending"
                        @selected($task->status === 'pending')
                    >
                        Pending
                    </option>

                    <option
                        value="in_progress"
                        @selected($task->status === 'in_progress')
                    >
                        In Progress
                    </option>

                    <option
                        value="completed"
                        @selected($task->status === 'completed')
                    >
                        Completed
                    </option>

                </select>
            </div>

            <p>
                Deadline:
                {{ $task->deadline ?? 'No deadline' }}
            </p>

            <a href="{{ route('tasks.edit', $task) }}">
                Edit
            </a>

            <form
                action="{{ route('tasks.destroy', $task) }}"
                method="POST"
                style="display: inline;"
            >

                @csrf
                @method('DELETE')

                <button type="submit">
                    Delete
                </button>

            </form>

        </div>

        <hr>

    @empty

        <p>No tasks found.</p>

    @endforelse
    
    {{ $tasks->links() }}

    <script>
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content');

    document
        .querySelectorAll('.task-status')
        .forEach(function (select) {
            select.addEventListener(
                'change',
                async function () {
                    const taskId = this.dataset.taskId;
                    const status = this.value;
                    try {
                        const response = await fetch(
                            `/tasks/${taskId}/status`,
                            {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                },
                                body: JSON.stringify({
                                    status: status
                                }),
                            }
                        );
                        const data = await response.json();
                        if (!response.ok) {
                            throw new Error(
                                data.message
                                || 'Update failed.'
                            );
                        }
                        console.log(data.message);
                    } catch (error) {
                        alert(error.message);
                    }
                }
            );
        });
</script>    


</body>
</html>