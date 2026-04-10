<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $taskService) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['status', 'priority', 'search']);
        $tasks   = $this->taskService->getPaginatedTasks($filters);
        $stats   = $this->taskService->getStats();

        return view('tasks.index', compact('tasks', 'filters', 'stats'));
    }

    public function create(): View
    {
        return view('tasks.create');
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    { 
        $this->taskService->createTask($request->validated());

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task): View
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task): View
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $this->taskService->updateTask($task, $request->validated());

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->taskService->deleteTask($task);

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}
