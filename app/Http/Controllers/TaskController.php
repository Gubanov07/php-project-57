<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if (Auth::guest()) {
            return abort(403);
        }

        $statuses = TaskStatus::pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $labels = Label::pluck('name', 'id');

        return view('tasks.create', compact('statuses', 'users', 'labels'));
    }

    public function store(StoreTaskRequest $request)
    {
        if (Auth::guest()) {
            return redirect()->route('tasks.index');
        }

        $validated = $request->validated();

        $task = Task::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'status_id' => $validated['status_id'],
            'created_by_id' => Auth::id(),
            'assigned_to_id' => $validated['assigned_to_id'] ?? null,
        ]);

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
        if (Auth::guest()) {
            abort(403);
        }
        $statuses = TaskStatus::pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $labels = Label::pluck('name', 'id');

        return view('tasks.edit', compact('task', 'statuses', 'users', 'labels'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        if (Auth::guest()) {
            return redirect()->route('tasks.index');
        }
        
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
        if (Auth::id() === $task->created_by_id) {
            $task->labels()->detach();
            $task->delete();
            flash(__('controllers.tasks_destroy'))->success();
        } else {
            flash(__('controllers.tasks_destroy_failed'))->error();
        }
        return redirect()->route('tasks.index');
    }
}
