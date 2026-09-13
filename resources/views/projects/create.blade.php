<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Project</title>
</head>

<body>

    <h1>Create Project</h1>

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
        action="{{ route('projects.store') }}"
        method="POST"
    >

        @csrf

        <div>
            <label>Name</label>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
            >
        </div>

        <br>

        <div>
            <label>Description</label>

            <textarea
                name="description"
            >{{ old('description') }}</textarea>
        </div>

        <br>

        <button type="submit">
            Create
        </button>

    </form>

</body>
</html>