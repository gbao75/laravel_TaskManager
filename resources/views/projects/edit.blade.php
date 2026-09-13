<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Project</title>
</head>

<body>

    <h1>Edit Project</h1>

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
        action="{{ route('projects.update', $project) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        <div>
            <label>Name</label>

            <input
                type="text"
                name="name"
                value="{{ old('name', $project->name) }}"
            >
        </div>

        <br>

        <div>
            <label>Description</label>

            <textarea
                name="description"
            >{{ old('description', $project->description) }}</textarea>
        </div>

        <br>

        <button type="submit">
            Update
        </button>

    </form>

</body>
</html>