<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Projects</title>
</head>

<body>

    <h1>Projects</h1>

    @if (session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif

    <a href="{{ route('projects.create') }}">
        Create Project
    </a>

    <hr>

    @forelse ($projects as $project)

        <div>
            <h3>
                {{ $project->name }}
            </h3>

            <p>
                {{ $project->description }}
            </p>

            <a href="{{ route('projects.edit', $project) }}">
                Edit
            </a>

            <form
                action="{{ route('projects.destroy', $project) }}"
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

        <p>No projects found.</p>

    @endforelse

</body>
</html>