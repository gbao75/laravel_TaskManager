<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
{
    $query = auth()->user()
        ->tasks()
        ->with('project');

    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('project_id')) {
        $query->where('project_id', $request->project_id);
    }

    if ($request->sort === 'deadline_asc') {
        $query->orderBy('deadline', 'asc');
    } elseif ($request->sort === 'deadline_desc') {
        $query->orderBy('deadline', 'desc');
    } elseif ($request->sort === 'oldest') {
        $query->oldest();
    } else {
        $query->latest();
    }

    $tasks = $query
        ->paginate(2)
        ->withQueryString();

    $projects = auth()->user()
        ->projects()
        ->orderBy('name')
        ->get();

    return view('tasks.index', compact('tasks', 'projects'));
}

    public function create()
    {
        $projects = auth()->user()
            ->projects()
            ->orderBy('name')
            ->get();

        return view('tasks.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:pending,in_progress,completed'],
            'deadline' => ['nullable', 'date'],
        ]);

        $project = auth()->user()
            ->projects()
            ->findOrFail($validated['project_id']);

        auth()->user()->tasks()->create([
            'project_id' => $project->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'deadline' => $validated['deadline'] ?? null,
        ]);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $this->checkOwner($task);

        $projects = auth()->user()
            ->projects()
            ->orderBy('name')
            ->get();

        return view('tasks.edit', compact('task', 'projects'));
    }

    public function update(Request $request, Task $task)
    {
        $this->checkOwner($task);

        $validated = $request->validate([
            'project_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:pending,in_progress,completed'],
            'deadline' => ['nullable', 'date'],
        ]);

        $project = auth()->user()
            ->projects()
            ->findOrFail($validated['project_id']);

        $task->update([
            'project_id' => $project->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'deadline' => $validated['deadline'] ?? null,
        ]);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->checkOwner($task);

        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
    
    public function updateStatus(Request $request, Task $task) 
    {
        $this->checkOwner($task);

        $validated = $request->validate([
            'status' => [
                        'required',
                        'in:pending,in_progress,completed',
            ],
        ]);

        $task->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'status' => $task->status,
        ]);
    }

    private function checkOwner(Task $task)
    {
        abort_if(
            $task->user_id !== auth()->id(),
            403
        );
    }
}