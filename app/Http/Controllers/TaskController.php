<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('created_by_id'),
                AllowedFilter::exact('assigned_to_id'),
                AllowedFilter::partial('name'),
            ])
            ->with(['status', 'creator', 'assignee'])
            ->paginate(10)
            ->withQueryString();

        $statuses = TaskStatus::pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $labels = Label::pluck('name', 'id');

        return view('tasks.index', compact('tasks', 'statuses', 'users', 'labels'));
    }

    public function create()
    {
        $this->authorize('create', Task::class);
        $statuses = TaskStatus::pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $labels = Label::pluck('name', 'id');

        return view('tasks.create', compact('statuses', 'users', 'labels'));
    }

    public function store(StoreTaskRequest $request)
    {
        $this->authorize('create', Task::class);

        $validated = $request->validated();
        $validated['created_by_id'] = auth()->id();

        $task = Task::create($validated);

        if (isset($validated['labels'])) {
            $task->labels()->attach($validated['labels']);
        }

        flash(__('controllers.tasks_create'))->success();
        return redirect()->route('tasks.index');
    }

    public function show(Task $task)
    {
        $task->load(['status', 'creator', 'assignee', 'labels']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('create', $task);
        $statuses = TaskStatus::pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $labels = Label::pluck('name', 'id');

        return view('tasks.edit', compact('task', 'statuses', 'users', 'labels'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validated();
        $task->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status_id' => $validated['status_id'],
            'assigned_to_id' => $validated['assigned_to_id'] ?? null,
        ]);

        $task->labels()->sync($validated['labels'] ?? []);

        flash(__('controllers.tasks_update'))->success();
        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task)
    {
        try {
            $this->authorize('delete', $task);
        } catch (AuthorizationException) {
            flash(__('controllers.tasks_destroy_failed'))->error();
            return redirect()->route('tasks.index');
        }

        $task->labels()->detach();
        $task->delete();
        flash(__('controllers.tasks_destroy'))->success();

        return redirect()->route('tasks.index');
    }
}
