<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('status', 'all');
        $filter = in_array($filter, ['all', 'pending', 'completed'], true) ? $filter : 'all';

        $tasks = Task::query()
            ->when($filter !== 'all', fn ($query) => $query->where('status', $filter))
            ->orderByRaw("CASE WHEN status = 'completed' THEN 1 ELSE 0 END")
            ->orderByRaw('due_date IS NULL')
            ->orderBy('due_date')
            ->latest()
            ->get();

        $counts = [
            'all' => Task::count(),
            'pending' => Task::where('status', 'pending')->count(),
            'completed' => Task::where('status', 'completed')->count(),
        ];

        return view('tasks.index', compact('tasks', 'counts', 'filter'));
    }

    public function create(): View
    {
        return view('tasks.form', ['task' => new Task()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Task::create($this->validatedTask($request));

        return redirect()->route('tasks.index')->with('success', 'Task added to your list.');
    }

    public function edit(Task $task): View
    {
        return view('tasks.form', compact('task'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $task->update($this->validatedTask($request));

        return redirect()->route('tasks.index')->with('success', 'Task changes saved.');
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,completed'],
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task status updated.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }

    private function validatedTask(Request $request): array
    {
        return $request->validate([
            'task_name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:pending,completed'],
            'due_date' => ['nullable', 'date'],
        ]);
    }
}