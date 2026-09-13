<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Task</title>
</head>

<body>

    <h1>Edit Task</h1>

    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <p style="color: red;">
                    {{ $error }}
                </p>
            @endforeach
        </div>
    @endif

    <form
        action="{{ route('tasks.update', $task) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        <div>
            <label>Project</label>

            <select name="project_id">

                @foreach ($projects as $project)

                    <option
                        value="{{ $project->id }}"
                        @selected(
                            old('project_id', $task->project_id)
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
            <label>Title</label>

            <input
                type="text"
                name="title"
                value="{{ old('title', $task->title) }}"
            >
        </div>

        <br>

        <div>
            <label>Description</label>

            <textarea name="description">{{ old('description', $task->description) }}</textarea>
        </div>

        <br>

        <div>
            <label>Status</label>

            <select name="status">

                <option
                    value="pending"
                    @selected(
                        old('status', $task->status) === 'pending'
                    )
                >
                    Pending
                </option>

                <option
                    value="in_progress"
                    @selected(
                        old('status', $task->status) === 'in_progress'
                    )
                >
                    In Progress
                </option>

                <option
                    value="completed"
                    @selected(
                        old('status', $task->status) === 'completed'
                    )
                >
                    Completed
                </option>

            </select>
        </div>

        <br>

        <div>
            <label>Deadline</label>

            <input
                type="date"
                name="deadline"
                value="{{ old('deadline', $task->deadline) }}"
            >
        </div>

        <br>

        <button type="submit">
            Update Task
        </button>

    </form>

</body>
</html>